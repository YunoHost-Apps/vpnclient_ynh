<!--
Важно: этот README был автоматически сгенерирован <https://github.com/YunoHost/apps/tree/master/tools/readme_generator>
Он НЕ ДОЛЖЕН редактироваться вручную.
-->

# VPN Client для YunoHost

[![Уровень интеграции](https://apps.yunohost.org/badge/integration/vpnclient)](https://ci-apps.yunohost.org/ci/apps/vpnclient/)
![Состояние работы](https://apps.yunohost.org/badge/state/vpnclient)
![Состояние сопровождения](https://apps.yunohost.org/badge/maintained/vpnclient)

[![Установите VPN Client с YunoHost](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[Прочтите этот README на других языках.](./ALL_README.md)*

> *Этот пакет позволяет Вам установить VPN Client быстро и просто на YunoHost-сервер.*  
> *Если у Вас нет YunoHost, пожалуйста, посмотрите [инструкцию](https://yunohost.org/install), чтобы узнать, как установить его.*

## Обзор

Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**Поставляемая версия:** 2.2~ynh6

## Снимки экрана

![Снимок экрана VPN Client](./doc/screenshots/vpnclient.png)

## Документация и ресурсы

- Официальный веб-сайт приложения: <https://labriqueinter.net>
- Магазин YunoHost: <https://apps.yunohost.org/app/vpnclient>
- Сообщите об ошибке: <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## Информация для разработчиков

Пришлите Ваш запрос на слияние в [ветку `testing`](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing).

Чтобы попробовать ветку `testing`, пожалуйста, сделайте что-то вроде этого:

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
или
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**Больше информации о пакетировании приложений:** <https://yunohost.org/packaging_apps>
