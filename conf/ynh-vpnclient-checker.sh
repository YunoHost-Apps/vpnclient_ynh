#!/bin/bash

if [[ ! -e /tmp/.ynh-vpnclient-started ]] && ! ip route get 1.2.3.4 | grep -q tun0; then
  systemctl restart ynh-vpnclient &> /dev/null
fi

exit 0
