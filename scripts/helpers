#!/bin/bash

source /usr/share/yunohost/helpers

#
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
