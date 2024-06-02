<!--
Ohart ongi: README hau automatikoki sortu da <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>ri esker
EZ editatu eskuz.
-->

# VPN Client YunoHost-erako

[![Integrazio maila](https://dash.yunohost.org/integration/vpnclient.svg)](https://dash.yunohost.org/appci/app/vpnclient) ![Funtzionamendu egoera](https://ci-apps.yunohost.org/ci/badges/vpnclient.status.svg) ![Mantentze egoera](https://ci-apps.yunohost.org/ci/badges/vpnclient.maintain.svg)

[![Instalatu VPN Client YunoHost-ekin](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Irakurri README hau beste hizkuntzatan.](./ALL_README.md)*

> *Pakete honek VPN Client YunoHost zerbitzari batean azkar eta zailtasunik gabe instalatzea ahalbidetzen dizu.*  
> *YunoHost ez baduzu, kontsultatu [gida](https://yunohost.org/install) nola instalatu ikasteko.*

## Aurreikuspena

Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**Paketatutako bertsioa:** 2.2~ynh4

## Pantaila-argazkiak

![VPN Client(r)en pantaila-argazkia](./doc/screenshots/vpnclient.png)

## Dokumentazioa eta baliabideak

- Aplikazioaren webgune ofiziala: <https://labriqueinter.net>
- YunoHost Denda: <https://apps.yunohost.org/app/vpnclient>
- Eman errore baten berri: <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Garatzaileentzako informazioa

Bidali `pull request`a [`testing` abarrera](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing).

`testing` abarra probatzeko, ondorengoa egin:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
edo
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Informazio gehiago aplikazioaren paketatzeari buruz:** <https://yunohost.org/packaging_apps>
