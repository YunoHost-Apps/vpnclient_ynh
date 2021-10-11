* Installez une connexion VPN sur votre serveur auto-hébergé
* Utile pour héberger votre serveur derrière un accès internet filtré (et/ou non-neutre)
* Utile pour obtenir une IP statique (v4 et v6)
* Utile pour pouvoir facilement déplacer votre serveur
* Pare-feu strict (le traffice entrant et sortant se fait seulement via le pare-feu et ne fuite pas de données à votre FAI commercial)
* Peut-être combiné avec [l'application Hotspot](https://github.com/YunoHost-Apps/hotspot_ynh) pour diffuser un WiFi protégé par le VPN à d'autres laptop sans configuration technique requise sur les machines clientes.

## Faire tourner VPNclient dans un LXC

Si vous souhaitez faire tourner OpenVPN dans un LXC, il vous faudra rajouter la configuration suivante dans votre conteneur:
```
lxc.hook.autodev = sh -c "modprobe tun"
lxc.mount.entry=/dev/net/tun dev/net/tun none bind,create=file
lxc.hook.autodev = sh -c "chmod 0666 dev/net/tun"
```
