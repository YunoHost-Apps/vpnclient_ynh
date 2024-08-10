<!--
N.B.: README ini dibuat secara otomatis oleh <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Ini TIDAK boleh diedit dengan tangan.
-->

# VPN Client untuk YunoHost

[![Tingkat integrasi](https://dash.yunohost.org/integration/vpnclient.svg)](https://ci-apps.yunohost.org/ci/apps/vpnclient/) ![Status kerja](https://ci-apps.yunohost.org/ci/badges/vpnclient.status.svg) ![Status pemeliharaan](https://ci-apps.yunohost.org/ci/badges/vpnclient.maintain.svg)

[![Pasang VPN Client dengan YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Baca README ini dengan bahasa yang lain.](./ALL_README.md)*

> *Paket ini memperbolehkan Anda untuk memasang VPN Client secara cepat dan mudah pada server YunoHost.*  
> *Bila Anda tidak mempunyai YunoHost, silakan berkonsultasi dengan [panduan](https://yunohost.org/install) untuk mempelajari bagaimana untuk memasangnya.*

## Ringkasan

Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**Versi terkirim:** 2.2~ynh4

## Tangkapan Layar

![Tangkapan Layar pada VPN Client](./doc/screenshots/vpnclient.png)

## Dokumentasi dan sumber daya

- Website aplikasi resmi: <https://labriqueinter.net>
- Gudang YunoHost: <https://apps.yunohost.org/app/vpnclient>
- Laporkan bug: <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Info developer

Silakan kirim pull request ke [`testing` branch](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing).

Untuk mencoba branch `testing`, silakan dilanjutkan seperti:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
atau
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Info lebih lanjut mengenai pemaketan aplikasi:** <https://yunohost.org/packaging_apps>
