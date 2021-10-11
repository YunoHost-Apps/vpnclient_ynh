Please note that this application is designed to interface with **dedicated, public IP VPNs accepting inbound traffic**, preferably with an associated `.cube` (or `.ovpn/.conf`) configuration file. **Do not** expect that any VPN you randomly bought on the Internet can be used! Checkout the [list of known compatible providers](https://yunohost.org/providers/vpn) for more info.


## Running vpnclient inside lxc

If you want to run openvpn inside lxc, you should add this to your container:
```
lxc.hook.autodev = sh -c "modprobe tun"
lxc.mount.entry=/dev/net/tun dev/net/tun none bind,create=file
lxc.hook.autodev = sh -c "chmod 0666 dev/net/tun"
```
