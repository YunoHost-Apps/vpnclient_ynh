<?php


dispatch('/', 'hello_world');
function hello_world() {
    flash('success', T_('This is a notification'));
    set('title', T_('Hello World !'));
    return render('homepage.html.php');
}

dispatch('/lang/:locale', 'changeLocale');
function changeLocale ($locale = 'en') {
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
}

