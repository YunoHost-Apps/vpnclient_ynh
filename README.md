# VPN Client
## Overview

**Warning: work in progress**

**Warning: currently, there is no checking on input parameters**

VPN Client app for [YunoHost](http://yunohost.org/).

* Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* With the [Hotspot app for YunoHost](https://github.com/jvaubourg/hotspot_ynh), you can broadcast your VPN access by Wifi for using a clean internet connection (depending on your VPN provider) on your laptop (or those of your friends) without have to configure it.

## Features

* Port selection, with UDP or TCP
* Authentication based on certificates (and an optional login)
* IPv6 compliant (with a delegated prefix)
* Set an IPv6 from your delegated prefix (*prefix::1*) on the server, to use for the AAAA records
* Use native IPv6 if available for creating the tunnel
* The internet provider can be a 3/4G connection with tethering
* Web interface ([screenshot](https://raw.githubusercontent.com/jvaubourg/vpnclient_ynh/master/screenshot.png))
