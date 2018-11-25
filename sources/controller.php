<?php

/* VPN Client app for YunoHost 
 * Copyright (C) 2015 Julien Vaubourg <julien@vaubourg.com>
 * Contribute at https://github.com/labriqueinternet/vpnclient_ynh
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

function ynh_setting_get($setting) {
  $value = exec("sudo grep \"^$setting:\" /etc/yunohost/apps/vpnclient/settings.yml");
  $value = preg_replace('/^[^:]+:\s*["\']?/', '', $value);
  $value = preg_replace('/\s*["\']$/', '', $value);

  return htmlspecialchars($value);
}

function ynh_setting_set($setting, $value) {
  return exec('sudo yunohost app setting vpnclient '.escapeshellarg($setting).' -v '.escapeshellarg($value));
}

function stop_service() {
  touch('/tmp/.ynh-vpnclient-stopped');
  exec('sudo systemctl stop ynh-vpnclient');
}

function start_service() {
  exec('sudo systemctl start ynh-vpnclient', $output, $retcode);
  unlink('/tmp/.ynh-vpnclient-stopped');

  return $retcode;
}

function service_status() {
  exec('sudo ynh-vpnclient status', $output);

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

function noneValue($str) {
  return ($str == 'none') ? '' : $str;
}

function readAutoConf($file) {
  $json = file_get_contents($file);
  $config = json_decode($json, true);

  if(!empty($config['crt_server_ca'])) {
    $config['crt_server_ca'] = str_replace('|', "\n", $config['crt_server_ca']);
  }

  if(!empty($config['crt_client'])) {
    $config['crt_client'] = str_replace('|', "\n", $config['crt_client']);
  }

  if(!empty($config['crt_client_key'])) {
    $config['crt_client_key'] = str_replace('|', "\n", $config['crt_client_key']);
  }

  if(!empty($config['crt_client_ta'])) {
    $config['crt_client_ta'] = str_replace('|', "\n", $config['crt_client_ta']);
  }

  return $config;
}

dispatch('/', function() {
  $ip6_net = noneValue(ynh_setting_get('ip6_net'));
  $raw_openvpn = file_get_contents('/etc/openvpn/client.conf.tpl');

  set('service_enabled', ynh_setting_get('service_enabled'));
  set('server_name', noneValue(ynh_setting_get('server_name')));
  set('server_port', ynh_setting_get('server_port'));
  set('server_proto', ynh_setting_get('server_proto'));
  set('login_user', ynh_setting_get('login_user'));
  set('login_passphrase', ynh_setting_get('login_passphrase'));
  set('ip6_net', $ip6_net);
  set('crt_client_exists', file_exists('/etc/openvpn/keys/user.crt'));
  set('crt_client_key_exists', file_exists('/etc/openvpn/keys/user.key'));
  set('crt_client_ta_exists', file_exists('/etc/openvpn/keys/user_ta.key'));
  set('crt_server_ca_exists', file_exists('/etc/openvpn/keys/ca-server.crt'));
  set('faststatus', service_faststatus() == 0);
  set('raw_openvpn', $raw_openvpn);
  set('dns0', ynh_setting_get('dns0'));
  set('dns1', ynh_setting_get('dns1'));

  return render('settings.html.php');
});

dispatch_put('/settings', function() {

  if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    throw new Exception('CSRF protection');
  }

  $service_enabled = isset($_POST['service_enabled']) ? 1 : 0;

  if($service_enabled == 1) {
    $crt_client_exists = file_exists('/etc/openvpn/keys/user.crt');
    $crt_client_key_exists = file_exists('/etc/openvpn/keys/user.key');
    $crt_server_ca_exists = file_exists('/etc/openvpn/keys/ca-server.crt');

    $config = $_POST;
    $autoconf = false;

    try {
      if($_FILES['cubefile']['error'] == UPLOAD_ERR_OK) {
        $config = readAutoConf($_FILES['cubefile']['tmp_name']);

        if(is_null($config)) {
          throw new Exception(_('Json Syntax Error, please check your dot cube file'));
        }

        $autoconf = true;
      }
  
      $ip6_net = empty($config['ip6_net']) ? 'none' : $config['ip6_net'];
      $ip6_addr = 'none';

      if(empty($config['server_name']) || empty($config['server_port']) || empty($config['server_proto'])) {
        throw new Exception(_('The Server Address, the Server Port and the Protocol cannot be empty'));
      }
    
      if(!preg_match('/^\d+$/', $config['server_port'])) {
        throw new Exception(_('The Server Port must be only composed of digits'));
      }
    
      if($config['server_proto'] != 'udp' && $config['server_proto'] != 'tcp') {
        throw new Exception(_('The Protocol must be "udp" or "tcp"'));
      }

      if(empty($config['dns0']) || empty($config['dns1'])) {
        throw new Exception(_('You need to define two DNS resolver addresses'));
      }

      if(empty($config['login_user']) xor empty($config['login_passphrase'])) {
        throw new Exception(_('A Password is needed when you suggest a Username, or vice versa'));
      }

      if((!$autoconf && (($_FILES['crt_client']['error'] == UPLOAD_ERR_OK && $_FILES['crt_client_key']['error'] != UPLOAD_ERR_OK && (!$crt_client_key_exists || $_POST['crt_client_key_delete'] == 1))
        || ($_FILES['crt_client_key']['error'] == UPLOAD_ERR_OK && $_FILES['crt_client']['error'] != UPLOAD_ERR_OK && (!$crt_client_exists || $_POST['crt_client_delete'] == 1))))
        || ($autoconf && (empty($config['crt_client']) xor empty($config['crt_client_key'])))) {
      
        throw new Exception(_('A Client Certificate is needed when you suggest a Key, or vice versa'));
      } 
 
      if((!$autoconf && $_FILES['crt_server_ca']['error'] != UPLOAD_ERR_OK && !$crt_server_ca_exists) || ($autoconf && empty($config['crt_server_ca']))) {
        throw new Exception(_('You need a Server CA.'));
      }
      
      if(((!$autoconf && $_FILES['crt_client_key']['error'] != UPLOAD_ERR_OK && (!$crt_client_key_exists || $_POST['crt_client_key_delete'] == 1)) || ($autoconf && empty($config['crt_client_key']))) && empty($config['login_user'])) {
        throw new Exception(_('You need either a Client Certificate, either a Username, or both'));
      }
    
      if($ip6_net != 'none') {
        $ip6_net = ipv6_expanded($ip6_net);
    
        if(empty($ip6_net)) {
          throw new Exception(_('The IPv6 Delegated Prefix format looks bad'));
        }
    
        $ip6_blocs = explode(':', $ip6_net);
        $ip6_addr = "${ip6_blocs[0]}:${ip6_blocs[1]}:${ip6_blocs[2]}:${ip6_blocs[3]}:${ip6_blocs[4]}:${ip6_blocs[5]}:${ip6_blocs[6]}:42";
    
        $ip6_net = ipv6_compressed($ip6_net);
        $ip6_addr = ipv6_compressed($ip6_addr);
      }

    } catch(Exception $e) {
      flash('error', $e->getMessage().' ('._('configuration not updated').').');
      goto redirect;
    }
  }
  
  stop_service();
  
  ynh_setting_set('service_enabled', $service_enabled);

  if($service_enabled == 1) {
    ynh_setting_set('server_name', $config['server_name']);
    ynh_setting_set('server_port', $config['server_port']);
    ynh_setting_set('server_proto', $config['server_proto']);
    ynh_setting_set('dns0', $config['dns0']);
    ynh_setting_set('dns1', $config['dns1']);
    ynh_setting_set('login_user', $config['login_user']);
    ynh_setting_set('login_passphrase', $config['login_passphrase']);
    ynh_setting_set('ip6_net', $ip6_net);
    ynh_setting_set('ip6_addr', $ip6_addr);

    if($autoconf) {
      copy('/etc/openvpn/client.conf.tpl.restore', '/etc/openvpn/client.conf.tpl');

      if(!empty($config['openvpn_rm'])) {
        $raw_openvpn = explode("\n", file_get_contents('/etc/openvpn/client.conf.tpl'));
        $fopenvpn = fopen('/etc/openvpn/client.conf.tpl', 'w');

        foreach($raw_openvpn AS $opt) {
          $filtered = false;

          if(!preg_match('/^#/', $opt) && !preg_match('/<TPL:/', $opt)) {
            foreach($config['openvpn_rm'] AS $filter) {
              if(!empty($filter) && preg_match("/$filter/i", $opt)) {
                $filtered = true;
              }
            }
          }

          if(!$filtered) {
            fwrite($fopenvpn, "$opt\n");
          }
        }

        fclose($fopenvpn);
      }

      if(!empty($config['openvpn_add'])) {
        $raw_openvpn = file_get_contents('/etc/openvpn/client.conf.tpl');
        $raw_openvpn .= "\n# Custom\n".implode("\n", $config['openvpn_add']);

        file_put_contents('/etc/openvpn/client.conf.tpl', $raw_openvpn);
      }

      if(empty($config['crt_client'])) {
        if(file_exists('/etc/openvpn/keys/user.crt')) {
          unlink('/etc/openvpn/keys/user.crt');
        }
      } else {
        file_put_contents('/etc/openvpn/keys/user.crt', $config['crt_client']);
      }

      if(empty($config['crt_client_key'])) {
        if(file_exists('/etc/openvpn/keys/user.key')) {
          unlink('/etc/openvpn/keys/user.key');
        }
      } else {
        file_put_contents('/etc/openvpn/keys/user.key', $config['crt_client_key']);
      }

      if(empty($config['crt_client_ta'])) {
        if(file_exists('/etc/openvpn/keys/user_ta.key')) {
          unlink('/etc/openvpn/keys/user_ta.key');
        }
      } else {
        file_put_contents('/etc/openvpn/keys/user_ta.key', $config['crt_client_ta']);
      }

      if(empty($config['crt_server_ca'])) {
        if(file_exists('/etc/openvpn/keys/ca-server.crt')) {
          unlink('/etc/openvpn/keys/ca-server.crt');
        }
      } else {
        file_put_contents('/etc/openvpn/keys/ca-server.crt', $config['crt_server_ca']);
      }

    } else {

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
  
      if($_FILES['crt_client_ta']['error'] == UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['crt_client_ta']['tmp_name'], '/etc/openvpn/keys/user_ta.key');
      } elseif($_POST['crt_client_ta_delete'] == 1) {
        unlink('/etc/openvpn/keys/user_ta.key');
      }
      
      if($_FILES['crt_server_ca']['error'] == UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['crt_server_ca']['tmp_name'], '/etc/openvpn/keys/ca-server.crt');
      }
    }
    
    if(!empty($config['login_user'])) {
      file_put_contents('/etc/openvpn/keys/credentials', "${config['login_user']}\n${config['login_passphrase']}");
    } else {
      file_put_contents('/etc/openvpn/keys/credentials', '');
    }

    $retcode = start_service();

    if($retcode == 0) {
      flash('success', _('Configuration updated and service successfully reloaded'));
    } else {
      flash('error', _('Configuration updated but service reload failed'));
    }

  } else {
      flash('success', _('Service successfully disabled'));
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
  switch($locale) {
    case 'fr':
      $_SESSION['locale'] = 'fr';
    break;

    default:
      $_SESSION['locale'] = 'en';
  }

  redirect_to('/');
});
