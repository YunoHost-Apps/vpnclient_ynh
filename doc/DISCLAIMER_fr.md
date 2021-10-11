Notez que cette application est prévue pour fonctionner avec des **VPN dédiés et à IP publique qui acceptent le traffic entrant**, et de préférence avec un fichier de configuration `.cube` (ou `.ovpn/.conf`) associé. Un VPN acheté au hasard sur Internet ne fonctionnera sans doute pas ! Consultez [la liste des fournisseurs connus et compatibles](https://yunohost.org/providers/vpn) pour plus d'infos.

## Faire tourner VPNclient dans un LXC

Si vous souhaitez faire tourner OpenVPN dans un LXC, il vous faudra rajouter la configuration suivante dans votre conteneur:
```
lxc.hook.autodev = sh -c "modprobe tun"
lxc.mount.entry=/dev/net/tun dev/net/tun none bind,create=file
lxc.hook.autodev = sh -c "chmod 0666 dev/net/tun"
```
