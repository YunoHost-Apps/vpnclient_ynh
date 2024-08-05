<!--
Este archivo README esta generado automaticamente<https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
No se debe editar a mano.
-->

# VPN Client para Yunohost

[![Nivel de integración](https://dash.yunohost.org/integration/vpnclient.svg)](https://ci-apps.yunohost.org/ci/apps/vpnclient/) ![Estado funcional](https://ci-apps.yunohost.org/ci/badges/vpnclient.status.svg) ![Estado En Mantención](https://ci-apps.yunohost.org/ci/badges/vpnclient.maintain.svg)

[![Instalar VPN Client con Yunhost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Leer este README en otros idiomas.](./ALL_README.md)*

> *Este paquete le permite instalarVPN Client rapidamente y simplement en un servidor YunoHost.*  
> *Si no tiene YunoHost, visita [the guide](https://yunohost.org/install) para aprender como instalarla.*

## Descripción general

Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**Versión actual:** 2.2~ynh4

## Capturas

![Captura de VPN Client](./doc/screenshots/vpnclient.png)

## Documentaciones y recursos

- Sitio web oficial: <https://labriqueinter.net>
- Catálogo YunoHost: <https://apps.yunohost.org/app/vpnclient>
- Reportar un error: <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Información para desarrolladores

Por favor enviar sus correcciones a la [`branch testing`](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing

Para probar la rama `testing`, sigue asÍ:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
o
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Mas informaciones sobre el empaquetado de aplicaciones:** <https://yunohost.org/packaging_apps>
