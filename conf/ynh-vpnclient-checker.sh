#!/bin/bash

if [ ! -e /tmp/.ynh-vpnclient-stopped ] && ! ip link show tun0 &> /dev/null; then
  systemctl restart ynh-vpnclient &> /dev/null
fi

exit 0
