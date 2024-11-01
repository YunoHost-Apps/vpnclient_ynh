<!--
N.B.: Questo README è stato automaticamente generato da <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
NON DEVE essere modificato manualmente.
-->

# VPN Client per YunoHost

[![Livello di integrazione](https://dash.yunohost.org/integration/vpnclient.svg)](https://dash.yunohost.org/appci/app/vpnclient) ![Stato di funzionamento](https://ci-apps.yunohost.org/ci/badges/vpnclient.status.svg) ![Stato di manutenzione](https://ci-apps.yunohost.org/ci/badges/vpnclient.maintain.svg)

[![Installa VPN Client con YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Leggi questo README in altre lingue.](./ALL_README.md)*

> *Questo pacchetto ti permette di installare VPN Client su un server YunoHost in modo semplice e veloce.*  
> *Se non hai YunoHost, consulta [la guida](https://yunohost.org/install) per imparare a installarlo.*

## Panoramica

* Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**Versione pubblicata:** 2.1.2~ynh1

## Screenshot

![Screenshot di VPN Client](./doc/screenshots/vpnclient.png)

## Attenzione/informazioni importanti

Please note that this application is designed to interface with **dedicated, public IP VPNs accepting inbound traffic**, preferably with an associated `.cube` (or `.ovpn/.conf`) configuration file. **Do not** expect that any VPN you randomly bought on the Internet can be used! Checkout the [list of known compatible providers](https://yunohost.org/providers/vpn) for more info.

## Documentazione e risorse

- Store di YunoHost: <https://apps.yunohost.org/app/vpnclient>
- Segnala un problema: <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Informazioni per sviluppatori

Si prega di inviare la tua pull request alla [branch di `testing`](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing).

Per provare la branch di `testing`, si prega di procedere in questo modo:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
o
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Maggiori informazioni riguardo il pacchetto di quest’app:** <https://yunohost.org/packaging_apps>
