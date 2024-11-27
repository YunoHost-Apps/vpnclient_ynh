<!--
To README zostało automatycznie wygenerowane przez <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Nie powinno być ono edytowane ręcznie.
-->

# VPN Client dla YunoHost

[![Poziom integracji](https://apps.yunohost.org/badge/integration/vpnclient)](https://ci-apps.yunohost.org/ci/apps/vpnclient/)
![Status działania](https://apps.yunohost.org/badge/state/vpnclient)
![Status utrzymania](https://apps.yunohost.org/badge/maintained/vpnclient)

[![Zainstaluj VPN Client z YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Przeczytaj plik README w innym języku.](./ALL_README.md)*

> *Ta aplikacja pozwala na szybką i prostą instalację VPN Client na serwerze YunoHost.*  
> *Jeżeli nie masz YunoHost zapoznaj się z [poradnikiem](https://yunohost.org/install) instalacji.*

## Przegląd

Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**Dostarczona wersja:** 2.2~ynh6

## Zrzuty ekranu

![Zrzut ekranu z VPN Client](./doc/screenshots/vpnclient.png)

## Dokumentacja i zasoby

- Oficjalna strona aplikacji: <https://labriqueinter.net>
- Sklep YunoHost: <https://apps.yunohost.org/app/vpnclient>
- Zgłaszanie błędów: <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Informacje od twórców

Wyślij swój pull request do [gałęzi `testing`](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing).

Aby wypróbować gałąź `testing` postępuj zgodnie z instrukcjami:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
lub
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Więcej informacji o tworzeniu paczek aplikacji:** <https://yunohost.org/packaging_apps>
