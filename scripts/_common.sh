#!/bin/bash
#
# Common variables and helpers
#

pkg_dependencies="php5-fpm sipcalc dnsutils openvpn curl fake-hwclock"

log() {
  echo "${1}"
}

info() {
  log "[INFO] ${1}"
}

warn() {
  log "[WARN] ${1}"
}

err() {
  log "[ERR] ${1}"
}
to_logs() {

  # When yunohost --verbose or bash -x
  if $_ISVERBOSE; then
    cat
  else
    cat > /dev/null
  fi
}

# Experimental helpers
# Cf. https://github.com/YunoHost-Apps/Experimental_helpers/blob/72b0bc77c68d4a4a2bf4e95663dbc05e4a762a0a/ynh_read_manifest/ynh_read_manifest
read_json () {
    python3 -c "import sys, json;print(json.load(open('$1'))['$2'])"
}

# Experimental helper
# Cf. https://github.com/YunoHost-Apps/Experimental_helpers/blob/72b0bc77c68d4a4a2bf4e95663dbc05e4a762a0a/ynh_read_manifest/ynh_read_manifest
read_manifest () {
    if [ -f '../manifest.json' ] ; then
        read_json '../manifest.json' "$1"
    else
        read_json '../settings/manifest.json' "$1"
    fi
}

# Experimental helper
# cf. https://github.com/YunoHost-Apps/Experimental_helpers/blob/master/ynh_abort_if_up_to_date/ynh_abort_if_up_to_date
ynh_abort_if_up_to_date () {
    version=$(read_json "/etc/yunohost/apps/$YNH_APP_INSTANCE_NAME/manifest.json" 'version' 2> /dev/null || echo '20160501-7')
    last_version=$(read_manifest 'version')
    if [ "${version}" = "${last_version}" ]; then
        info "Up-to-date, nothing to do"
        ynh_die "" 0
    fi
}

# Helper to start/stop/.. a systemd service from a yunohost context,
# *and* the systemd service itself needs to be able to run yunohost
# commands.
#
# Hence the need to release the lock during the operation
#
# usage : ynh_systemctl yolo restart
#
function ynh_systemctl()
{
  local ACTION="$1"
  local SERVICE="$2"
  local LOCKFILE="/var/run/moulinette_yunohost.lock"

  # Launch the action
  systemctl "$ACTION" "$SERVICE" &
  local SYSCTLACTION=$!

  # Save and release the lock...
  cp $LOCKFILE $LOCKFILE.bkp.$$
  rm $LOCKFILE

  # Wait for the end of the action
  wait $SYSCTLACTION

  # Make sure the lock is released...
  while [ -f $LOCKFILE ]
  do
    sleep 0.1
  done

  # Restore the old lock
  mv $LOCKFILE.bkp.$$ $LOCKFILE
}

# Read the value of a key in a ynh manifest file
#
# usage: ynh_read_manifest manifest key
# | arg: manifest - Path of the manifest to read
# | arg: key - Name of the key to find
ynh_read_manifest () {
	manifest="$1"
	key="$2"
	python3 -c "import sys, json;print(json.load(open('$manifest', encoding='utf-8'))['$key'])"
}

# Read the upstream version from the manifest
# The version number in the manifest is defined by <upstreamversion>~ynh<packageversion>
# For example : 4.3-2~ynh3
# This include the number before ~ynh
# In the last example it return 4.3-2
#
# usage: ynh_app_upstream_version
ynh_app_upstream_version () {
    manifest_path="../manifest.json"
    if [ ! -e "$manifest_path" ]; then
        manifest_path="../settings/manifest.json"	# Into the restore script, the manifest is not at the same place
    fi
    version_key=$(ynh_read_manifest "$manifest_path" "version")
    echo "${version_key/~ynh*/}"
}

# Read package version from the manifest
# The version number in the manifest is defined by <upstreamversion>~ynh<packageversion>
# For example : 4.3-2~ynh3
# This include the number after ~ynh
# In the last example it return 3
#
# usage: ynh_app_package_version
ynh_app_package_version () {
    manifest_path="../manifest.json"
    if [ ! -e "$manifest_path" ]; then
        manifest_path="../settings/manifest.json"	# Into the restore script, the manifest is not at the same place
    fi
    version_key=$(ynh_read_manifest "$manifest_path" "version")
    echo "${version_key/*~ynh/}"
}

# Exit without error if the package is up to date
#
# This helper should be used to avoid an upgrade of a package
# when it's not needed.
#
# To force an upgrade, even if the package is up to date,
# you have to set the variable YNH_FORCE_UPGRADE before.
# example: YNH_FORCE_UPGRADE=1 yunohost app upgrade MyApp
#
# usage: ynh_abort_if_up_to_date
ynh_abort_if_up_to_date () {
	local force_upgrade=${YNH_FORCE_UPGRADE:-0}
	local package_check=${PACKAGE_CHECK_EXEC:-0}

	local version=$(ynh_read_manifest "/etc/yunohost/apps/$YNH_APP_INSTANCE_NAME/manifest.json" "version" || echo 1.0)
	local last_version=$(ynh_read_manifest "../manifest.json" "version" || echo 1.0)
	if [ "$version" = "$last_version" ]
	then
		if [ "$force_upgrade" != "0" ]
		then
			echo "Upgrade forced by YNH_FORCE_UPGRADE." >&2
			unset YNH_FORCE_UPGRADE
		elif [ "$package_check" != "0" ]
		then
			echo "Upgrade forced for package check." >&2
		else
			ynh_die "Up-to-date, nothing to do" 0
		fi
	fi
}

# Operations needed by both 'install' and 'upgrade' scripts
function vpnclient_deploy_files_and_services()
{
  local domain=$1
  local app=$2
  local sysuser="${app}"

  # Ensure vpnclient_ynh has its own system user
  if ! ynh_system_user_exists ${sysuser}
  then
    ynh_system_user_create ${sysuser}
  fi

  # Ensure the system user has enough permissions
  install -b -o root -g root -m 0440 ../conf/sudoers.conf /etc/sudoers.d/${app}_ynh
  ynh_replace_string "__VPNCLIENT_SYSUSER__" "${sysuser}" /etc/sudoers.d/${app}_ynh

  # Install IPv6 scripts
  install -o root -g root -m 0755 ../conf/ipv6_expanded /usr/local/bin/
  install -o root -g root -m 0755 ../conf/ipv6_compressed /usr/local/bin/

  # Install command-line cube file loader
  install -o root -g root -m 0755 ../conf/ynh-vpnclient-loadcubefile.sh /usr/local/bin/

  # Copy confs
  mkdir -pm 0755 /var/log/nginx/
  chown root:${sysuser} /etc/openvpn/
  chmod 775 /etc/openvpn/
  mkdir -pm 0755 /etc/yunohost/hooks.d/post_iptable_rules/

  install -b -o root -g ${sysuser} -m 0664 ../conf/openvpn_client.conf.tpl /etc/openvpn/client.conf.tpl
  install -o root -g root -m 0644 ../conf/openvpn_client.conf.tpl /etc/openvpn/client.conf.tpl.restore
  install -b -o root -g root -m 0644 ../conf/nginx_vpnadmin.conf "/etc/nginx/conf.d/${domain}.d/${app}.conf"
  install -b -o root -g root -m 0644 ../conf/phpfpm_vpnadmin.conf /etc/php5/fpm/pool.d/${app}.conf
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
  chown root:${sysuser} /etc/openvpn/keys/

  #=================================================
  # NGINX CONFIGURATION
  #=================================================

  sed "s|<TPL:NGINX_LOCATION>|${path_url}|g" -i "/etc/nginx/conf.d/${domain}.d/${app}.conf"
  sed "s|<TPL:NGINX_REALPATH>|/var/www/${app}/|g" -i "/etc/nginx/conf.d/${domain}.d/${app}.conf"
  sed "s|<TPL:PHP_NAME>|${app}|g" -i "/etc/nginx/conf.d/${domain}.d/${app}.conf"

  #=================================================
  # PHP-FPM CONFIGURATION
  #=================================================

  sed "s|<TPL:PHP_NAME>|${app}|g" -i /etc/php5/fpm/pool.d/${app}.conf
  sed "s|<TPL:PHP_USER>|${sysuser}|g" -i /etc/php5/fpm/pool.d/${app}.conf
  sed "s|<TPL:PHP_GROUP>|${sysuser}|g" -i /etc/php5/fpm/pool.d/${app}.conf
  sed "s|<TPL:NGINX_REALPATH>|/var/www/${app}/|g" -i /etc/php5/fpm/pool.d/${app}.conf

  # Fix sources
  sed "s|<TPL:NGINX_LOCATION>|${path_url}|g" -i /var/www/${app}/config.php

  # Copy init script
  install -o root -g root -m 0755 ../conf/ynh-vpnclient /usr/local/bin/
  install -o root -g root -m 0644 ../conf/ynh-vpnclient.service /etc/systemd/system/

  # Copy checker timer
  install -o root -g root -m 0755 ../conf/ynh-vpnclient-checker.sh /usr/local/bin/
  install -o root -g root -m 0644 ../conf/ynh-vpnclient-checker.service /etc/systemd/system/
  install -o root -g root -m 0644 ../conf/ynh-vpnclient-checker.timer /etc/systemd/system/

  systemctl daemon-reload
}
