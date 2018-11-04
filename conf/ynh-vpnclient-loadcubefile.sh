#!/bin/bash

# VPN Client app for YunoHost
# Copyright (C) 2015 Julien Vaubourg <julien@vaubourg.com>
# Contribute at https://github.com/labriqueinternet/vpnclient_ynh
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

# Options

while getopts "u:p:c:h" opt; do
  case $opt in
    u)
      ynh_user=$OPTARG
    ;;
    p)
      ynh_password=$OPTARG
    ;;
    c)
      cubefile_path=$OPTARG

      if [ ! -r "${cubefile_path}" ]; then
        echo "[ERR] Cube file does not exist or is unreadable" >&2
        exit 1
      fi
    ;;
    h)
      echo "-u YunoHost username (user with permissions on VPN Client)"
      echo "-p User password"
      echo "-c Dot cube file path"
      echo "-h This help"

      exit 0
    ;;
    \?)
      echo "[ERR] Invalid option (-h for help)" >&2
      exit 1
    ;;
  esac
done

if [ -z "${ynh_user}" ]; then
  echo "[ERR] Option -u is mandatory (-h for help)" >&2
  exit 1
fi

if [ -z "${ynh_password}" ]; then
  echo "[ERR] Option -p is mandatory (-h for help)" >&2
  exit 1
fi

if [ -z "${cubefile_path}" ]; then
  echo "[ERR] Option -c is mandatory (-h for help)" >&2
  exit 1
fi


# Other variables

ynh_setting() {
  app=${1}
  setting=${2}

  sudo grep "^${setting}:" "/etc/yunohost/apps/${app}/settings.yml" | sed s/^[^:]\\+:\\s*[\"\']\\?// | sed s/\\s*[\"\']\$//
}

tmpdir=$(mktemp -dp /tmp/ vpnclient-loadcubefile-XXXXX)

cubefile_ip6=$(sed -n '/ip6_net/ { s/.*"\([0-9a-zA-Z:]\+\)".*/\1/p }' "${cubefile_path}")

ynh_domain=$(ynh_setting vpnclient domain)
ynh_path=$(ynh_setting vpnclient path)
ynh_service_enabled=$(ynh_setting vpnclient service_enabled)


# SSO login

curl -kLe "https://${ynh_domain}/yunohost/sso/" --data-urlencode "user=${ynh_user}" --data-urlencode "password=${ynh_password}" "https://${ynh_domain}/yunohost/sso/" --resolve "${ynh_domain}:443:127.0.0.1" -c "${tmpdir}/cookies" 2> /dev/null | grep -q Logout

if [ $? -ne 0 ]; then
  echo "[ERROR] SSO login failed" >&2
  exit 1
fi


# Upload cube file

output=$(curl -kL -H "X-Requested-With: yunohost-config" -F "service_enabled=${ynh_service_enabled}" -F _method=put -F "cubefile=@${cubefile_path}" "https://${ynh_domain}/${ynh_path}/?/settings" --resolve "${ynh_domain}:443:127.0.0.1" -b "${tmpdir}/cookies" 2> /dev/null | grep RETURN_MSG | sed 's/<!-- RETURN_MSG -->//' | sed 's/<\/?[^>]\+>//g' | sed 's/^ \+//g')


# Configure IPv6 Delegated Prefix on Hotspot

if [ ! -z "${cubefile_ip6}" ] && (sudo yunohost app info hotspot | grep -q Hotspot); then
  ynh_multissid=$(ynh_setting hotspot multissid)

  if [ "${ynh_multissid}" -eq 1 ]; then
    ynh_ip6_net=$(ynh_setting vpnclient ip6_net)
    ynh_ip6_addr=$(ynh_setting vpnclient ip6_addr)

    sudo systemctl stop ynh-hotspot &> /dev/null
    sudo yunohost app setting hotspot ip6_net -v "${ynh_ip6_net}"
    sudo yunohost app setting hotspot ip6_addr -v "${ynh_ip6_addr}"
    sudo systemctl start ynh-hotspot &> /dev/null

    echo "[INFO] Wifi Hotspot found with only one SSID: IPv6 Delegated Prefix automatically configured" >&2
  fi
fi


# Done!

echo [VPN] $output
(echo $output | grep -q Error) && exit 1

rm -r "${tmpdir}/"

exit 0
