<?php

// Limonade configuration
function configure() {
    option('env', ENV_PRODUCTION);
    option('debug', false);
    option('base_uri', '<TPL:NGINX_LOCATION>/');
    layout("layout.html.php");
    define('PUBLIC_DIR', '<TPL:NGINX_LOCATION>/public');
}

// Not found page
function not_found($errno, $errstr, $errfile=null, $errline=null) {
    $msg = h(rawurldecode($errstr));
    return render($msg, 'error_layout.html.php');
}

function T_($string) {
    return gettext($string);
}

// Before routing
function before($route) {
     /**
     * * Locale
     * */
    if (!isset($_SESSION['locale'])) {
        $locale = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $_SESSION['locale'] = strtolower(substr(chop($locale[0]),0,2));
    }
    $textdomain="localization";
    putenv('LANGUAGE='.$_SESSION['locale']);
    putenv('LANG='.$_SESSION['locale']);
    putenv('LC_ALL='.$_SESSION['locale']);
    putenv('LC_MESSAGES='.$_SESSION['locale']);
    setlocale(LC_ALL,$_SESSION['locale']);
    setlocale(LC_CTYPE,$_SESSION['locale']);
    $locales_dir = dirname(__FILE__).'/../i18n';
    bindtextdomain($textdomain,$locales_dir);
    bind_textdomain_codeset($textdomain, 'UTF-8');
    textdomain($textdomain);
    // Set the $locale variable in template
    set('locale', $_SESSION['locale']);
}

// After routing
function after($output, $route) {
    /*
    $time = number_format( (float)substr(microtime(), 0, 10) - LIM_START_MICROTIME, 6);
    $output .= "\n<!-- page rendered in $time sec., on ".date(DATE_RFC822)." -->\n";
    $output .= "<!-- for route\n";
    $output .= print_r($route, true);
    $output .= "-->";
    */
    return $output;
}
