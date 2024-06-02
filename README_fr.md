<!--
Nota bene : ce README est automatiquement généré par <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Il NE doit PAS être modifié à la main.
-->

# VPN Client pour YunoHost

[![Niveau d’intégration](https://dash.yunohost.org/integration/vpnclient.svg)](https://dash.yunohost.org/appci/app/vpnclient) ![Statut du fonctionnement](https://ci-apps.yunohost.org/ci/badges/vpnclient.status.svg) ![Statut de maintenance](https://ci-apps.yunohost.org/ci/badges/vpnclient.maintain.svg)

[![Installer VPN Client avec YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Lire le README dans d'autres langues.](./ALL_README.md)*

> *Ce package vous permet d’installer VPN Client rapidement et simplement sur un serveur YunoHost.*  
> *Si vous n’avez pas YunoHost, consultez [ce guide](https://yunohost.org/install) pour savoir comment l’installer et en profiter.*

## Vue d’ensemble

Installez une connexion VPN sur votre serveur auto-hébergé
* Utile pour héberger votre serveur derrière un accès internet filtré (et/ou non-neutre)
* Utile pour obtenir une IP statique (v4 et v6)
* Utile pour pouvoir facilement déplacer votre serveur
* Pare-feu strict (le traffice entrant et sortant se fait seulement via le pare-feu et ne fuite pas de données à votre FAI commercial)
* Peut-être combiné avec [l'application Hotspot](https://github.com/YunoHost-Apps/hotspot_ynh) pour diffuser un WiFi protégé par le VPN à d'autres laptop sans configuration technique requise sur les machines clientes.



**Version incluse :** 2.2~ynh4

## Captures d’écran

![Capture d’écran de VPN Client](./doc/screenshots/vpnclient.png)

## Documentations et ressources

- Site officiel de l’app : <https://labriqueinter.net>
- YunoHost Store : <https://apps.yunohost.org/app/vpnclient>
- Signaler un bug : <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Informations pour les développeurs

Merci de faire vos pull request sur la [branche `testing`](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing).

Pour essayer la branche `testing`, procédez comme suit :

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
ou
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Plus d’infos sur le packaging d’applications :** <https://yunohost.org/packaging_apps>
