<?php

function moulinette_get($var) {
  return htmlspecialchars(exec("sudo yunohost app setting vpnclient ".escapeshellarg($var)));
}

function moulinette_set($var, $value) {
  return exec("sudo yunohost app setting vpnclient ".escapeshellarg($var)." -v ".escapeshellarg($value));
}

function stop_service() {
  exec('sudo service ynh-vpnclient stop');
}

function start_service() {
  exec('sudo service ynh-vpnclient start', $output, $retcode);

  return $retcode;
}

dispatch('/', function() {
  $ip6_net = moulinette_get('ip6_net');
  $ip6_net = ($ip6_net == 'none') ? '' : $ip6_net;

  set('server_name', moulinette_get('server_name'));
  set('server_port', moulinette_get('server_port'));
  set('server_proto', moulinette_get('server_proto'));
  set('login_user', moulinette_get('login_user'));
  set('login_passphrase', moulinette_get('login_passphrase'));
  set('ip6_net', $ip6_net);

  return render('settings.html.php');
});

dispatch_put('/settings', function() {
  $ip6_net = empty($_POST['ip6_net']) ? 'none' : $_POST['ip6_net'];

  stop_service();

  moulinette_set('server_name', $_POST['server_name']);
  moulinette_set('server_port', $_POST['server_port']);
  moulinette_set('server_proto', $_POST['server_proto']);
  moulinette_set('login_user', $_POST['login_user']);
  moulinette_set('login_passphrase', $_POST['login_passphrase']);
  moulinette_set('ip6_net', $ip6_net);

  # TODO: format ip6_net
  if($ip6_net == 'none') {
    moulinette_set('ip6_addr', 'none');
  } else {
    $ip6_addr = "${ip6_net}1";
    moulinette_set('ip6_addr', $ip6_addr);
  }

  if($_FILES['crt_client']['error'] == UPLOAD_ERR_OK) {
    move_uploaded_file($_FILES['crt_client']['tmp_name'], '/etc/openvpn/keys/user.crt');
  }

  if($_FILES['crt_client_key']['error'] == UPLOAD_ERR_OK) {
    move_uploaded_file($_FILES['crt_client_key']['tmp_name'], '/etc/openvpn/keys/user.key');
  }

  if($_FILES['crt_server_ca']['error'] == UPLOAD_ERR_OK) {
    move_uploaded_file($_FILES['crt_server_ca']['tmp_name'], '/etc/openvpn/keys/ca-server.crt');
  }

  $retcode = start_service();

  if($retcode == 0) {
    flash('success', T_('Configuration updated and service successfully reloaded'));
  } else {
    flash('error', T_('Configuration updated but service reload failed'));
  }

  redirect_to('/');
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
