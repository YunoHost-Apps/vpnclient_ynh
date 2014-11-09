<?php

function moulinette_get($var) {
  return htmlspecialchars(exec("sudo yunohost app setting vpnclient ".escapeshellarg($var)));
}

function moulinette_set($var, $value) {
  return exec("sudo yunohost app setting vpnclient ".escapeshellarg($var)." -v ".escapeshellarg($value));
}

function restart_service() {
  exec('sudo service ynh-vpnclient stop');
  exec('sudo service ynh-vpnclient start', $output, $retcode);

  return $retcode;
}

dispatch('/', function() {
  set('server_name', moulinette_get('server_name'));
  set('server_port', moulinette_get('server_port'));
  set('server_proto', moulinette_get('server_proto'));

  return render('settings.html.php');
});

dispatch_put('/settings', function() {
  moulinette_set('server_name', $_POST['server_name']);
  moulinette_set('server_port', $_POST['server_port']);
  moulinette_set('server_proto', $_POST['server_proto']);

  if($_FILES['crt_client']['error'] == UPLOAD_ERR_OK) {
    move_uploaded_file($_FILES['crt_client']['tmp_name'], '/etc/openvpn/keys/user.crt');
  }

  if($_FILES['crt_client_key']['error'] == UPLOAD_ERR_OK) {
    move_uploaded_file($_FILES['crt_client_key']['tmp_name'], '/etc/openvpn/keys/user.key');
  }

  if($_FILES['crt_server_ca']['error'] == UPLOAD_ERR_OK) {
    move_uploaded_file($_FILES['crt_server_ca']['tmp_name'], '/etc/openvpn/keys/ca-server.crt');
  }

  $retcode = restart_service();

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
