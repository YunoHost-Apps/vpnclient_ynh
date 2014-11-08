<?php


dispatch('/', function() {
    set('title', T_('Configure your VPN client'));
    return render('settings.html.php');
});

dispatch_put('/settings', function() {
    $success_message = "";
    foreach ($_POST as $key => $value) {
        $success_message = $success_message.T_("Parameter ").$key.": ".$value."<br>";
    }
    flash('success', $success_message);
    redirect_to('/');
});

dispatch('/lang/:locale', function($locale = 'en') {
    switch ($locale) {
        case 'fr':
            $_SESSION['locale'] = 'fr';
            break;
        default:
            $_SESSION['locale'] = 'en';
            break;
    }
    if(!empty($_GET['redirect_to']))
        redirect_to($_GET['redirect_to']);
    else
        redirect_to('/');
});

