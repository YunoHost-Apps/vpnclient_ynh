<!--
NOTA: Este README foi creado automáticamente por <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
NON debe editarse manualmente.
-->

# VPN Client para YunoHost

[![Nivel de integración](https://apps.yunohost.org/badge/integration/vpnclient)](https://ci-apps.yunohost.org/ci/apps/vpnclient/)
![Estado de funcionamento](https://apps.yunohost.org/badge/state/vpnclient)
![Estado de mantemento](https://apps.yunohost.org/badge/maintained/vpnclient)

[![Instalar VPN Client con YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Le este README en outros idiomas.](./ALL_README.md)*

> *Este paquete permíteche instalar VPN Client de xeito rápido e doado nun servidor YunoHost.*  
> *Se non usas YunoHost, le a [documentación](https://yunohost.org/install) para saber como instalalo.*

## Vista xeral

Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**Versión proporcionada:** 2.2~ynh6

## Capturas de pantalla

![Captura de pantalla de VPN Client](./doc/screenshots/vpnclient.png)

## Documentación e recursos

- Web oficial da app: <https://labriqueinter.net>
- Tenda YunoHost: <https://apps.yunohost.org/app/vpnclient>
- Informar dun problema: <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Info de desenvolvemento

Envía a túa colaboración á [rama `testing`](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing).

Para probar a rama `testing`, procede deste xeito:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
ou
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Máis info sobre o empaquetado da app:** <https://yunohost.org/packaging_apps>
