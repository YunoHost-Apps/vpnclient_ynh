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

if [ -z "${cubefile_path}" ]; then
  echo "[ERR] Option -c is mandatory (-h for help)" >&2
  exit 1
fi


sudo yunohost app config set vpnclient --args "config_file=${cubefile_path}"
