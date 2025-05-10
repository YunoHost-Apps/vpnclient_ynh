# [WARN] Edit this raw configuration ONLY IF YOU KNOW 
#        what you do!
# [WARN] Continue to use the placeholders and
#        keep update their value on the web admin (they 
#        are not only used for this file).

remote __SERVER_NAME__
proto __SERVER_PROTO__
port __SERVER_PORT__

pull
nobind
dev tun
tun-ipv6
keepalive 10 30
comp-lzo adaptive
resolv-retry infinite

# Authentication by login
__LOGIN_COMMENT__auth-user-pass /etc/openvpn/keys/credentials

# UDP only
__UDP_COMMENT__explicit-exit-notify

# TLS
tls-client
__TA_COMMENT__tls-auth /etc/openvpn/keys/user_ta.key 1
remote-cert-tls server
ns-cert-type server
ca /etc/openvpn/keys/ca-server.crt
__CERT_COMMENT__cert /etc/openvpn/keys/user.crt
__CERT_COMMENT__key /etc/openvpn/keys/user.key

# Logs
verb 3
mute 5
status /var/log/vpnclient/openvpn-client.status
log-append /var/log/vpnclient/openvpn-client.log

# Routing
route-ipv6 2000::/3
redirect-gateway def1 bypass-dhcp

script-security 2
route-up "/etc/openvpn/scripts/run-parts.sh route-up"
down "/etc/openvpn/scripts/run-parts.sh route-down"
