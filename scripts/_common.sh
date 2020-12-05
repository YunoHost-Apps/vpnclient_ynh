#!/bin/bash
#
# Common variables and helpers
#

YNH_PHP_VERSION="7.3"

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

  # Ensure the system user has enough permissions
  install -b -o root -g root -m 0440 ../conf/sudoers.conf /etc/sudoers.d/${app}_ynh
  ynh_replace_string "__VPNCLIENT_SYSUSER__" "${app}" /etc/sudoers.d/${app}_ynh

  # Install IPv6 scripts
  install -o root -g root -m 0755 ../conf/ipv6_expanded /usr/local/bin/
  install -o root -g root -m 0755 ../conf/ipv6_compressed /usr/local/bin/

  # Install command-line cube file loader
  install -o root -g root -m 0755 ../conf/$service_name-loadcubefile.sh /usr/local/bin/

  # Copy confs
  mkdir -pm 0755 /var/log/nginx/
  chown root:${app} /etc/openvpn/
  chmod 775 /etc/openvpn/
  mkdir -pm 0755 /etc/yunohost/hooks.d/post_iptable_rules/

  install -b -o root -g ${app} -m 0664 ../conf/openvpn_client.conf.tpl /etc/openvpn/client.conf.tpl
  install -o root -g root -m 0644 ../conf/openvpn_client.conf.tpl /etc/openvpn/client.conf.tpl.restore
  install -b -o root -g root -m 0755 ../conf/hook_post-iptable-rules /etc/yunohost/hooks.d/90-vpnclient.tpl
  install -b -o root -g root -m 0644 ../conf/openvpn@.service /etc/systemd/system/

  # Copy web sources
  mkdir -pm 0755 /var/www/${app}/
  cp -a ../sources/* /var/www/${app}/

  chown -R root: /var/www/${app}/
  chmod -R 0644 /var/www/${app}/*
  find /var/www/${app}/ -type d -exec chmod +x {} \;

  # Create certificates directory
  mkdir -pm 0770 /etc/openvpn/keys/
  chown root:${app} /etc/openvpn/keys/

  #=================================================
  # NGINX CONFIGURATION
  #=================================================
  ynh_print_info "Configuring nginx web server..."

  ynh_add_nginx_config

  #=================================================
  # PHP-FPM CONFIGURATION
  #=================================================
  ynh_print_info "Configuring PHP-FPM..."

  # Create a dedicated PHP-FPM config
  ynh_add_fpm_config --phpversion=$YNH_PHP_VERSION
  phpversion=$(ynh_app_setting_get --app=$app --key=phpversion)

  #=================================================

  # Fix sources
  ynh_replace_string "__PATH__" "${path_url%%/}" "/var/www/${app}/config.php"

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
