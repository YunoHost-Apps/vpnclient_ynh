# VPN Client [![Build Status](https://travis-ci.org/labriqueinternet/vpnclient_ynh.svg?branch=master)](https://travis-ci.org/labriqueinternet/vpnclient_ynh) [![Integration level](https://dash.yunohost.org/integration/vpnclient.svg)](https://dash.yunohost.org/appci/app/vpnclient)
[![Install LaBriqueInterNet VPNclient with YunoHost](https://install-app.yunohost.org/install-with-yunohost.png)](https://install-app.yunohost.org/?app=vpnclient)

This YunoHost app is a part of the "[La Brique Internet](http://labriqueinter.net)" project but can be used independently.

## Overview

VPN Client app for [YunoHost](http://yunohost.org/).

* Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* With the [Hotspot app for YunoHost](https://github.com/labriqueinternet/hotspot_ynh), you can broadcast your VPN access by wifi to use a clean internet connection (depending on your VPN provider) on your laptop (or those of your friends) without having to configure it.

## Features

* Authentication based on certificates or login (or both), with or without shared-secret (*ta.key*)
* IPv6 compliant (with a delegated prefix)
* Set an IPv6 from your delegated prefix (*prefix::42*) on the server, to use for the AAAA records
* Use native IPv6 if available for creating the tunnel
* Port selection, with UDP or TCP
* Set DNS resolvers on the host
* Strong firewalling (internet access and self-hosted services only available through the VPN)
* Advanced mode for editing the default OpenVPN configuration
* Auto-configuration mode, with [dot cube files](http://internetcu.be/dotcubefiles.html)
* Web interface

## Screenshot

![Screenshot of the web interface](https://raw.githubusercontent.com/labriqueinternet/vpnclient_ynh/master/screenshot.png)

