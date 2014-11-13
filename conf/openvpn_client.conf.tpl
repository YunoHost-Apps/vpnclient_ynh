remote <TPL:SERVER_NAME>

# proto [ udp6 | udp | tcp6-client | tcp-client ]
proto <TPL:PROTO>

pull
nobind
dev tun
tun-ipv6
keepalive 10 30
comp-lzo adaptive
port <TPL:SERVER_PORT>

# Auth by credentials
<TPL:LOGIN_COMMENT>auth-user-pass /etc/openvpn/keys/credentials

# UDP only
<TPL:UDP_COMMENT>explicit-exit-notify

# TLS
tls-client
remote-cert-tls server
<TPL:CERT_COMMENT>cert /etc/openvpn/keys/user.crt
<TPL:CERT_COMMENT>key /etc/openvpn/keys/user.key
ca /etc/openvpn/keys/ca-server.crt

# Logs
verb 3
mute 5
status /var/log/openvpn-client.status
log-append /var/log/openvpn-client.log

# Routing
route-ipv6 2000::/3
redirect-gateway def1 bypass-dhcp
