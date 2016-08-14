#!/bin/bash

if ! ip link show tun0 &> /dev/null; then
  systemctl restart ynh-vpnclient &> /dev/null
fi

exit 0
