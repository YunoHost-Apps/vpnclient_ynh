<?php

/* VPN Client app for YunoHost 
 * Copyright (C) 2015 Julien Vaubourg <julien@vaubourg.com>
 * Contribute at https://github.com/jvaubourg/vpnclient_ynh
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function moulinette_get($var) {
  return htmlspecialchars(exec('sudo yunohost app setting vpnclient '.escapeshellarg($var)));
}

function moulinette_set($var, $value) {
  return exec('sudo yunohost app setting vpnclient '.escapeshellarg($var).' -v '.escapeshellarg($value));
}

function stop_service() {
  exec('sudo systemctl stop ynh-vpnclient');
}

function start_service() {
  exec('sudo systemctl start ynh-vpnclient', $output, $retcode);

  return $retcode;
}

function service_status() {
  exec('sudo systemctl is-active ynh-vpnclient', $output);

  return $output;
}

function service_faststatus() {
  exec('ip link show tun0', $output, $retcode);

  return $retcode;
}

function ipv6_expanded($ip) {
  exec('ipv6_expanded '.escapeshellarg($ip), $output);

  return $output[0];
}

function ipv6_compressed($ip) {
  exec('ipv6_compressed '.escapeshellarg($ip), $output);

  return $output[0];
}

dispatch('/', function() {
  $ip6_net = moulinette_get('ip6_net');
  $ip6_net = ($ip6_net == 'none') ? '' : $ip6_net;
  $raw_openvpn = file_get_contents('/etc/openvpn/client.conf.tpl');

  set('service_enabled', moulinette_get('service_enabled'));
  set('server_name', moulinette_get('server_name'));
  set('server_port', moulinette_get('server_port'));
  set('server_proto', moulinette_get('server_proto'));
  set('login_user', moulinette_get('login_user'));
  set('login_passphrase', moulinette_get('login_passphrase'));
  set('ip6_net', $ip6_net);
  set('crt_client_exists', file_exists('/etc/openvpn/keys/user.crt'));
  set('crt_client_key_exists', file_exists('/etc/openvpn/keys/user.key'));
  set('crt_server_ca_exists', file_exists('/etc/openvpn/keys/ca-server.crt'));
  set('faststatus', service_faststatus() == 0);
  set('raw_openvpn', $raw_openvpn);

  return render('settings.html.php');
});

dispatch_put('/settings', function() {
  $crt_client_exists = file_exists('/etc/openvpn/keys/user.crt');
  $crt_client_key_exists = file_exists('/etc/openvpn/keys/user.key');
  $crt_server_ca_exists = file_exists('/etc/openvpn/keys/ca-server.crt');

  $service_enabled = isset($_POST['service_enabled']) ? 1 : 0;
  $ip6_net = empty($_POST['ip6_net']) ? 'none' : $_POST['ip6_net'];
  $ip6_addr = 'none';

  if($service_enabled == 1) {
    try {
      if(empty($_POST['server_name']) || empty($_POST['server_port']) || empty($_POST['server_proto'])) {
        throw new Exception(T_('The Server Address, the Server Port and the Protocol cannot be empty'));
      }
    
      if(!preg_match('/^\d+$/', $_POST['server_port'])) {
        throw new Exception(T_('The Server Port must be only composed of digits'));
      }
    
      if($_POST['server_proto'] != 'udp' && $_POST['server_proto'] != 'tcp') {
        throw new Exception(T_('The Protocol must be "udp" or "tcp"'));
      }
    
      if(($_FILES['crt_client']['error'] == UPLOAD_ERR_OK && $_FILES['crt_client_key']['error'] != UPLOAD_ERR_OK && (!$crt_client_key_exists || $_POST['crt_client_key_delete'] == 1))
        || ($_FILES['crt_client_key']['error'] == UPLOAD_ERR_OK && $_FILES['crt_client']['error'] != UPLOAD_ERR_OK && (!$crt_client_exists || $_POST['crt_client_delete'] == 1))) {
    
        throw new Exception(T_('A Client Certificate is needed when you suggest a Key, or vice versa'));
      }
    
      if(empty($_POST['login_user']) xor empty($_POST['login_passphrase'])) {
        throw new Exception(T_('A Password is needed when you suggest a Username, or vice versa'));
      }
    
      if($_FILES['crt_server_ca']['error'] != UPLOAD_ERR_OK && !$crt_server_ca_exists) {
        throw new Exception(T_('You need a Server CA.'));
      }
    
      if(($_FILES['crt_client_key']['error'] != UPLOAD_ERR_OK && (!$crt_client_key_exists || $_POST['crt_client_key_delete'] == 1)) && empty($_POST['login_user'])) {
        throw new Exception(T_('You need either a Client Certificate, either a Username, or both'));
      }
    
      if($ip6_net != 'none') {
        $ip6_net = ipv6_expanded($ip6_net);
    
        if(empty($ip6_net)) {
          throw new Exception(T_('The IPv6 Delegated Prefix format looks bad'));
        }
    
        $ip6_blocs = explode(':', $ip6_net);
        $ip6_addr = "${ip6_blocs[0]}:${ip6_blocs[1]}:${ip6_blocs[2]}:${ip6_blocs[3]}:${ip6_blocs[4]}:${ip6_blocs[5]}:${ip6_blocs[6]}:42";
    
        $ip6_net = ipv6_compressed($ip6_net);
        $ip6_addr = ipv6_compressed($ip6_addr);
      }

    } catch(Exception $e) {
      flash('error', $e->getMessage().' ('.T_('configuration not updated').').');
      goto redirect;
    }
  }
  
  stop_service();
  
  moulinette_set('service_enabled', $service_enabled);

  if($service_enabled == 1) {
    moulinette_set('server_name', $_POST['server_name']);
    moulinette_set('server_port', $_POST['server_port']);
    moulinette_set('server_proto', $_POST['server_proto']);
    moulinette_set('login_user', $_POST['login_user']);
    moulinette_set('login_passphrase', $_POST['login_passphrase']);
    moulinette_set('ip6_net', $ip6_net);
    moulinette_set('ip6_addr', $ip6_addr);
    
    file_put_contents('/etc/openvpn/client.conf.tpl', $_POST['raw_openvpn']);

    if($_FILES['crt_client']['error'] == UPLOAD_ERR_OK) {
      move_uploaded_file($_FILES['crt_client']['tmp_name'], '/etc/openvpn/keys/user.crt');
    } elseif($_POST['crt_client_delete'] == 1) {
      unlink('/etc/openvpn/keys/user.crt');
    }
    
    if($_FILES['crt_client_key']['error'] == UPLOAD_ERR_OK) {
      move_uploaded_file($_FILES['crt_client_key']['tmp_name'], '/etc/openvpn/keys/user.key');
    } elseif($_POST['crt_client_key_delete'] == 1) {
      unlink('/etc/openvpn/keys/user.key');
    }
    
    if($_FILES['crt_server_ca']['error'] == UPLOAD_ERR_OK) {
      move_uploaded_file($_FILES['crt_server_ca']['tmp_name'], '/etc/openvpn/keys/ca-server.crt');
    }
    
    if(!empty($_POST['login_user'])) {
      file_put_contents('/etc/openvpn/keys/credentials', "${_POST['login_user']}\n${_POST['login_passphrase']}");
    } else {
      file_put_contents('/etc/openvpn/keys/credentials', '');
    }

    $retcode = start_service();

    if($retcode == 0) {
      flash('success', T_('Configuration updated and service successfully reloaded'));
    } else {
      flash('error', T_('Configuration updated but service reload failed'));
    }

  } else {
      flash('success', T_('Service successfully disabled'));
  }

  redirect:
  redirect_to('/');
});

dispatch('/status', function() {
  $status_lines = service_status();
  $status_list = '';

  foreach($status_lines AS $status_line) {
    if(preg_match('/^\[INFO\]/', $status_line)) {
      $status_list .= '<li class="status-info">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[OK\]/', $status_line)) {
      $status_list .= '<li class="status-success">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[WARN\]/', $status_line)) {
      $status_list .= '<li class="status-warning">'.htmlspecialchars($status_line).'</li>';
    }
    elseif(preg_match('/^\[ERR\]/', $status_line)) {
      $status_list .= '<li class="status-danger">'.htmlspecialchars($status_line).'</li>';
    }
  }

  echo $status_list;
});

dispatch('/lang/:locale', function($locale = 'en') {
  switch ($locale) {
    case 'fr':
      $_SESSION['locale'] = 'fr';
      break;

    default:
      $_SESSION['locale'] = 'en';
  }

  if(!empty($_GET['redirect_to'])) {
    redirect_to($_GET['redirect_to']);
  } else {
    redirect_to('/');
  }
});
