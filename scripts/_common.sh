#!/bin/bash
#
# Common variables
#

pkg_dependencies="php5-fpm sipcalc dnsutils openvpn curl fake-hwclock"


# Experimental helpers
# Cf. https://github.com/YunoHost-Apps/Experimental_helpers/blob/72b0bc77c68d4a4a2bf4e95663dbc05e4a762a0a/ynh_read_manifest/ynh_read_manifest
read_json () {
    sudo python3 -c "import sys, json;print(json.load(open('$1'))['$2'])"
}
read_manifest () {
    if [ -f '../manifest.json' ] ; then
        read_json '../manifest.json' "$1"
    else
        read_json '../settings/manifest.json' "$1"
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
  sudo systemctl "$ACTION" "$SERVICE" &
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

