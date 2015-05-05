# VPN Client
## Overview

**Warning: work in progress**

VPN Client app for [YunoHost](http://yunohost.org/).

* Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* With the [Hotspot app for YunoHost](https://github.com/jvaubourg/hotspot_ynh), you can broadcast your VPN access by wifi to use a clean internet connection (depending on your VPN provider) on your laptop (or those of your friends) without having to configure it.

This YunoHost app is a part of the "[La Brique Internet](http://labriqueinter.net)" project but can be used independently.

## Features

* Port selection, with UDP or TCP
* Authentication based on certificates or login (or both)
* IPv6 compliant (with a delegated prefix)
* Set an IPv6 from your delegated prefix (*prefix::42*) on the server, to use for the AAAA records
* Use native IPv6 if available for creating the tunnel
* Web interface ([screenshot](https://raw.githubusercontent.com/jvaubourg/vpnclient_ynh/master/screenshot.png))

## Prerequisites

This app works with a non-stable version of YunoHost.

Until this version is available (coming soon!) as an official stable release, you need to execute some commands before installing this app:

    # service bind9 stop
    # update-rc.d bind9 remove
    # apt-get install dnsmasq
