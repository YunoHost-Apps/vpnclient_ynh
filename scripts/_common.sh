#!/bin/bash

service_name="ynh-vpnclient"
service_checker_name=$service_name"-checker"


# Operations needed by both 'install' and 'upgrade' scripts
function vpnclient_deploy_files_and_services()
{

  # Install command-line cube file loader
  install -o root -g root -m 0755 ../conf/$service_name-loadcubefile.sh /usr/local/bin/

  # Copy confs
  chown root:${app} /etc/openvpn/
  chmod 775 /etc/openvpn/
  mkdir -pm 0755 /etc/yunohost/hooks.d/post_iptable_rules/
  mkdir -pm 0755 /etc/systemd/system/openvpn@.service.d/

  install -b -o root -g root -m 0755 ../conf/hook_post-iptable-rules /etc/yunohost/hooks.d/90-vpnclient.tpl
  install -b -o root -g root -m 0644 ../conf/openvpn@.service /etc/systemd/system/openvpn@.service.d/override.conf

  # Create certificates directory
  mkdir -pm 0770 /etc/openvpn/keys/
  chown root:${app} /etc/openvpn/keys/

  # Create scripts directory
  mkdir -pm 0775 /etc/openvpn/scripts
  mkdir -pm 0775 /etc/openvpn/scripts/route-up.d
  mkdir -pm 0775 /etc/openvpn/scripts/route-down.d
  install -b -o root -g root -m 0755 ../conf/scripts/run-parts.sh /etc/openvpn/scripts/run-parts.sh
  install -b -o root -g root -m 0755 ../conf/scripts/route-up.d/* /etc/openvpn/scripts/route-up.d/
  install -b -o root -g root -m 0755 ../conf/scripts/route-down.d/* /etc/openvpn/scripts/route-down.d/

  #=================================================

  # Copy init script
  install -o root -g root -m 0755 ../conf/$service_name /usr/local/bin/

  # Copy checker timer
  install -o root -g root -m 0755 ../conf/$service_checker_name.sh /usr/local/bin/
  install -o root -g root -m 0644 ../conf/$service_checker_name.timer /etc/systemd/system/

  systemctl daemon-reload

  #=================================================
  # SETUP SYSTEMD
  #=================================================
  ynh_print_info "Configuring a systemd service..."

  ynh_add_systemd_config $service_name "$service_name.service"

  ynh_add_systemd_config $service_checker_name "$service_checker_name.service"
}

function read_cube() {
  local config_file="$1"
  local key="$2"
  local tmp_dir=$(dirname "$config_file")

  setting_value="$(jq --raw-output ".$key" "$config_file")"
  if [[ "$setting_value" == "null" ]]
  then
    setting_value=''
  # Save file in tmp dir
  elif [[ "$key" == "crt_"* ]]
  then
    if [ -n "${setting_value}" ]
    then
      echo "${setting_value}" | sed 's/|/\n/g' > "$tmp_dir/$key"
      setting_value="$tmp_dir/$key"
    fi
  fi
  echo $setting_value
}

function convert_cube_file()
{
  local config_file="$1"
  local tmp_dir=$(dirname "$config_file")
  
  ynh_print_info --message="Transforming .cube into OVPN file"
  server_name="$(read_cube $config_file server_name)"
  server_port="$(read_cube $config_file server_port)"
  server_proto="$(read_cube $config_file server_proto)"
  ip6_net="$(read_cube $config_file ip6_net)"
  ip6_addr="$(read_cube $config_file ip6_addr)"
  login_user="$(read_cube $config_file login_user)"
  login_passphrase="$(read_cube $config_file login_passphrase)"
  dns0="$(read_cube $config_file dns0)"
  dns1="$(read_cube $config_file dns1)"
  crt_server_ca="$(read_cube $config_file crt_server_ca)"
  crt_client="$(read_cube $config_file crt_client)"
  crt_client_key="$(read_cube $config_file crt_client_key)"
  crt_client_ta="$(read_cube $config_file crt_client_ta)"

  if [[ -z "$dns0" && -z "$dns1" ]]; then
    dns_method="yunohost"
  else
    dns_method="custom"
    nameservers="$dns0,$dns1"
  fi
  
  # Build specific OVPN template
  config_template="$tmp_dir/client.conf.tpl"
  cp -f /etc/yunohost/apps/vpnclient/conf/openvpn_client.conf.tpl "$config_template"
  # Remove some lines
  jq --raw-output '.openvpn_rm[]' "${config_file}" | while read -r rm_regex
  do
    if [ ! -z "${rm_regex}" ]; then
      sed -i "/${rm_regex/\//\\\/}/d" "$config_template"
    fi
  done

  # Add some other lines
  echo "# Custom additions from .cube" >> "$config_template"
  jq --raw-output ".openvpn_add[]" "${config_file}" >> "$config_template"

  # Temporarily tweak sever_proto for template hydratation
  if [ "$server_proto" == tcp ]; then
    server_proto=tcp-client
  fi

  # Define other needed vars for template hydratation
  [ -e "$crt_client_key" ] && cert_comment="" || cert_comment="#"
  [ -e "$crt_client_ta" ] && ta_comment="" || ta_comment="#"
  [[ "$server_proto" =~ udp ]] && udp_comment="" || udp_comment="#"
  [ -n "$login_user" ] && login_comment="" || login_comment="#"

  # Actually generate/hydrate the final configuration
  ynh_add_config --template="$config_template" --destination="$config_file"

  if [ "$server_proto" == tcp-client ]; then
    server_proto=tcp
  fi
}

function convert_ovpn_file()
{
  local config_file="$1"
  local tmp_dir=$(dirname "$config_file")

  ynh_print_info --message="Extracting TLS keys from .ovpn file"
  if grep -q '^\s*<ca>' ${config_file}
  then
    grep -Poz '(?<=<ca>)(.*\n)*.*(?=</ca>)' ${config_file} | sed '/^$/d'  > $tmp_dir/crt_server_ca
    crt_server_ca=$tmp_dir/crt_server_ca
    sed -i '/^\s*<ca>/,/\s*<\/ca>/d' ${config_file}
    sed -i '/^\s*ca\s/d' ${config_file}
    echo -e "\nca /etc/openvpn/keys/ca-server.crt" >> $config_file
  fi
  if grep -q '^\s*<cert>' ${config_file}
  then
    grep -Poz '(?<=<cert>)(.*\n)*.*(?=</cert>)' ${config_file} | sed '/^$/d'  > $tmp_dir/crt_client
    crt_client=$tmp_dir/crt_client
    sed -i '/^\s*<cert>/,/\s*<\/cert>/d' ${config_file}
    sed -i '/^\s*cert\s/d' ${config_file}
    echo -e "\ncert /etc/openvpn/keys/user.crt" >> ${config_file}
  elif ! grep -q '^\s*cert\s' ${config_file}
  then
    crt_client=""
  fi
  if grep -q '^\s*<key>' ${config_file}
  then
    grep -Poz '(?<=<key>)(.*\n)*.*(?=</key>)' ${config_file} | sed '/^$/d' > $tmp_dir/crt_client_key
    crt_client_key=$tmp_dir/crt_client_key
    sed -i '/^\s*<key>/,/\s*<\/key>/d' ${config_file}
    sed -i '/^\s*key\s/d' ${config_file}
    echo -e "\nkey /etc/openvpn/keys/user.key" >> ${config_file}
  elif ! grep -q '^\s*key\s' ${config_file}
  then
    crt_client_key=""
  fi
  if grep -q '^\s*<tls-auth>' ${config_file}
  then
    grep -Poz '(?<=<tls-auth>)(.*\n)*.*(?=</tls-auth>)' ${config_file} | sed '/^$/d' > $tmp_dir/crt_client_ta
    crt_client_ta=$tmp_dir/crt_client_ta
    sed -i '/^\s*<tls-auth>/,/\s*<\/tls-auth>/d' ${config_file}
    sed -i '/^\s*tls-auth\s/d' ${config_file}
    echo -e "\ntls-auth /etc/openvpn/keys/user_ta.key 1" >> ${config_file}
  elif ! grep -q '^\s*tls-auth\s' ${config_file}
  then
    crt_client_ta=""
  fi
  sed -i 's@^\s*ca\s.*$@ca /etc/openvpn/keys/ca-server.crt@g' ${config_file}
  sed -i 's@^\s*cert\s.*$@cert /etc/openvpn/keys/user.crt@g' ${config_file}
  sed -i 's@^\s*key\s.*$@key /etc/openvpn/keys/user.key@g' ${config_file}
  sed -i 's@^\s*tls-auth\s.*$@tls-auth /etc/openvpn/keys/user_ta.key 1@g' ${config_file}

  script_security="script-security 2"
  if grep -q '^\s*script-security\s.*$' ${config_file}; then
    sed -i "s@^\s*script-security\s.*\$@$script_security@g" ${config_file}
  else
    echo "$script_security" >> ${config_file}
  fi

  route_up='route-up "/etc/openvpn/scripts/run-parts.sh route-up"'
  if grep -q '^\s*route-up\s.*$' ${config_file}; then
    sed -i "s@^\s*route-up\s.*\$@$route_up@g" ${config_file}
  else
    echo "$route_up" >> ${config_file}
  fi

  route_down='down "/etc/openvpn/scripts/run-parts.sh route-down"'
  if grep -q '^\s*down\s.*$' ${config_file}; then
    sed -i "s@^\s*down\s.*\$@$route_down@g" ${config_file}
  else
    echo "$route_down" >> ${config_file}
  fi

  # Currently we need root priviledge to create tun0
  sed -i '/^\s*user\s/d' ${config_file}
  sed -i '/^\s*group\s/d' ${config_file}
}
