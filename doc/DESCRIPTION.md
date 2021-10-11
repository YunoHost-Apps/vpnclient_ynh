* Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.

## Running vpnclient inside lxc

If you want to run openvpn inside lxc, you should add this to your container:
```
lxc.hook.autodev = sh -c "modprobe tun"
lxc.mount.entry=/dev/net/tun dev/net/tun none bind,create=file
lxc.hook.autodev = sh -c "chmod 0666 dev/net/tun"
```
