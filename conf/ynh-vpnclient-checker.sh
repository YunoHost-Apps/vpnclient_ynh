#!/bin/bash

if [[ -e /tmp/.ynh-vpnclient-started ]] || ip route get 1.2.3.4 | grep -q tun0; then
  echo "[INFO] Service is already running"
  exit 0
else
  echo "[INFO] Restarting VPN client service"
  yunohost service restart ynh-vpnclient &> /dev/null
fi
