<!--
NB: Deze README is automatisch gegenereerd door <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Hij mag NIET handmatig aangepast worden.
-->

# VPN Client voor Yunohost

[![Integratieniveau](https://apps.yunohost.org/badge/integration/vpnclient)](https://ci-apps.yunohost.org/ci/apps/vpnclient/)
![Mate van functioneren](https://apps.yunohost.org/badge/state/vpnclient)
![Onderhoudsstatus](https://apps.yunohost.org/badge/maintained/vpnclient)

[![VPN Client met Yunohost installeren](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Deze README in een andere taal lezen.](./ALL_README.md)*

> *Met dit pakket kun je VPN Client snel en eenvoudig op een YunoHost-server installeren.*  
> *Als je nog geen YunoHost hebt, lees dan [de installatiehandleiding](https://yunohost.org/install), om te zien hoe je 'm installeert.*

## Overzicht

Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**Geleverde versie:** 2.2~ynh6

## Schermafdrukken

![Schermafdrukken van VPN Client](./doc/screenshots/vpnclient.png)

## Documentatie en bronnen

- Officiele website van de app: <https://labriqueinter.net>
- YunoHost-store: <https://apps.yunohost.org/app/vpnclient>
- Meld een bug: <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Ontwikkelaarsinformatie

Stuur je pull request alsjeblieft naar de [`testing`-branch](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing).

Om de `testing`-branch uit te proberen, ga als volgt te werk:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
of
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Verdere informatie over app-packaging:** <https://yunohost.org/packaging_apps>
