#!/bin/bash
#
# Common variables and helpers
#

pkg_dependencies="sipcalc dnsutils openvpn curl fake-hwclock"

service_name="ynh-vpnclient"
service_checker_name=$service_name"-checker"


# Operations needed by both 'install' and 'upgrade' scripts
function vpnclient_deploy_files_and_services()
{
  # Ensure vpnclient_ynh has its own system user
  if ! ynh_system_user_exists ${app}
  then
    ynh_system_user_create ${app}
  fi

  # Install command-line cube file loader
  install -o root -g root -m 0755 ../conf/$service_name-loadcubefile.sh /usr/local/bin/

  # Copy confs
  chown root:${app} /etc/openvpn/
  chmod 775 /etc/openvpn/
  mkdir -pm 0755 /etc/yunohost/hooks.d/post_iptable_rules/
  mkdir -pm 0755 /etc/systemd/system/openvpn@.service.d/

  install -b -o root -g ${app} -m 0644 ../conf/openvpn_client.conf.tpl /etc/openvpn/client.conf.tpl
  install -b -o root -g root -m 0755 ../conf/hook_post-iptable-rules /etc/yunohost/hooks.d/90-vpnclient.tpl
  install -b -o root -g root -m 0644 ../conf/openvpn@.service /etc/systemd/system/openvpn@.service.d/override.conf

  # Create certificates directory
  mkdir -pm 0770 /etc/openvpn/keys/
  chown root:${app} /etc/openvpn/keys/

  # Create scripts directory
  mkdir -pm 0775 /etc/openvpn/scripts
  mkdir -pm 0775 /etc/openvpn/scripts/route-up.d
  mkdir -pm 0775 /etc/openvpn/scripts/route-down.d

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
