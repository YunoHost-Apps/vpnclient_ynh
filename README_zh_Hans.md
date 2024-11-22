<!--
注意：此 README 由 <https://github.com/YunoHost/apps/tree/master/tools/readme_generator> 自动生成
请勿手动编辑。
-->

# YunoHost 上的 VPN Client

[![集成程度](https://dash.yunohost.org/integration/vpnclient.svg)](https://ci-apps.yunohost.org/ci/apps/vpnclient/) ![工作状态](https://ci-apps.yunohost.org/ci/badges/vpnclient.status.svg) ![维护状态](https://ci-apps.yunohost.org/ci/badges/vpnclient.maintain.svg)

[![使用 YunoHost 安装 VPN Client](https://install-app.yunohost.org/install-with-yunohost.svg)](https://install-app.yunohost.org/?app=vpnclient)

*[阅读此 README 的其它语言版本。](./ALL_README.md)*

> *通过此软件包，您可以在 YunoHost 服务器上快速、简单地安装 VPN Client。*  
> *如果您还没有 YunoHost，请参阅[指南](https://yunohost.org/install)了解如何安装它。*

## 概况

Install a VPN connection on your self-hosted server.
* Useful for hosting your server behind a filtered (and/or non-neutral) internet access.
* Useful to have static IP addresses (IPv6 and IPv4).
* Useful to easily move your server anywhere.
* Strong firewalling (internet access and self-hosted services only available through the VPN, not leaking to your commercial ISP)
* Combine with the [Hotspot app](https://github.com/YunoHost-Apps/hotspot_ynh) to broadcast VPN-protected WiFi to other laptops without any further technical configuration needed.



**分发版本：** 2.2~ynh6

## 截图

![VPN Client 的截图](./doc/screenshots/vpnclient.png)

## 文档与资源

- 官方应用网站： <https://labriqueinter.net>
- YunoHost 商店： <https://apps.yunohost.org/app/vpnclient>
- 报告 bug： <https://github.com/YunoHost-Apps/vpnclient_ynh/issues>

## 开发者信息

请向 [`testing` 分支](https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing) 发送拉取请求。

如要尝试 `testing` 分支，请这样操作：

```bash
sudo yunohost app install https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
或
sudo yunohost app upgrade vpnclient -u https://github.com/YunoHost-Apps/vpnclient_ynh/tree/testing --debug
```

**有关应用打包的更多信息：** <https://yunohost.org/packaging_apps>
