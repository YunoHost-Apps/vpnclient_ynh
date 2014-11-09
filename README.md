# VPN Client
## Overview

**Warning: work in progress**

VPN Client app for [YunoHost](http://yunohost.org/).

* Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* With the [Wifi Hotspot app for YunoHost](https://github.com/jvaubourg/hotspot_ynh), you can broadcast your VPN access for using a clean internet connection (depending on your VPN provider) on your laptop (or those of your friends) without have to configure it.

Small computers like [Olimex](https://www.olimex.com) or [Raspberry PI](http://www.raspberrypi.org/) boxes and an USB Wifi dongle like [this one](https://www.olimex.com/Products/USB-Modules/MOD-WIFI-R5370-ANT/) are perfect for a nomade access with low power consumption.

## Features

* IPv6 compliant (with a delegated prefix)
* Port selection, with UDP or TCP
* Use native IPv6 if available for creating the tunnel
* Set an IPv6 from your delegated prefix (*prefix::1*) on the server, to use for the AAAA records
* The internet provider can be a 3/4G connection with tethering
* Web interface ([screenshot](https://raw.githubusercontent.com/jvaubourg/vpnclient_ynh/master/screenshot.png))
