# VPN Client
## Overview

**Warning: work in progress**

VPN Client app for [YunoHost](http://yunohost.org/).

* Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* With the [Hotspot app for YunoHost](https://github.com/jvaubourg/hotspot_ynh), you can broadcast your VPN access by Wifi for using a clean internet connection (depending on your VPN provider) on your laptop (or those of your friends) without have to configure it.

See the <a href="https://raw.githubusercontent.com/jvaubourg/hotspot_ynh/master/docs/box-project_french.pdf">box project</a> explanations (box for a ready-made and nomad self-hosting with YunoHost+VPN, a VPN access through a wifi hotspot, and/or a <a href="https://en.wikipedia.org/wiki/Customer-premises_equipment">CPE</a> for non-profit ISP) in French. An example of hardware associated is the <a href="https://www.olimex.com/Products/OLinuXino/A20/A20-OLinuXino-LIME/open-source-hardware">A20-OLinuXino-LIME</a> with the <a href="https://www.olimex.com/Products/USB-Modules/MOD-WIFI-R5370-ANT/">MOD-WIFI-R5370-ANT</a> (<a href="https://raw.githubusercontent.com/jvaubourg/hotspot_ynh/master/docs/box-project.png">photo</a>). See also the <a href="https://github.com/bleuchtang/olinuxino-a20-lime">YunoHost image project</a> for OLinuXino.

## Features

* Port selection, with UDP or TCP
* Authentication based on certificates or login (or both)
* IPv6 compliant (with a delegated prefix)
* Set an IPv6 from your delegated prefix (*prefix::42*) on the server, to use for the AAAA records
* Use native IPv6 if available for creating the tunnel
* Web interface ([screenshot](https://raw.githubusercontent.com/jvaubourg/vpnclient_ynh/master/screenshot.png))
