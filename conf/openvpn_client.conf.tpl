# [WARN] Edit this raw configuration ONLY IF YOU KNOW 
#        what you do!
# [WARN] Continue to use the placeholders <TPL:*> and
#        keep update their value on the web admin (they 
#        are not only used for this file).

remote <TPL:SERVER_NAME>
proto <TPL:PROTO>
port <TPL:SERVER_PORT>

pull
nobind
dev tun
tun-ipv6
keepalive 10 30
comp-lzo adaptive

# Authentication by login
<TPL:LOGIN_COMMENT>auth-user-pass /etc/openvpn/keys/credentials

# UDP only
<TPL:UDP_COMMENT>explicit-exit-notify

# TLS
tls-client
<TPL:TA_COMMENT>tls-auth /etc/openvpn/keys/user_ta.key 1
remote-cert-tls server
ca /etc/openvpn/keys/ca-server.crt
<TPL:CERT_COMMENT>cert /etc/openvpn/keys/user.crt
<TPL:CERT_COMMENT>key /etc/openvpn/keys/user.key

# Logs
verb 3
mute 5
status /var/log/openvpn-client.status
log-append /var/log/openvpn-client.log

# Routing
route-ipv6 2000::/3
redirect-gateway def1 bypass-dhcp
