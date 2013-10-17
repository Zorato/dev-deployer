<?php

// define some paths
if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

if (!defined('TMP_PATH')) {
    define('TMP_PATH', realpath(APPLICATION_PATH . '/tmp'));
}

if (!defined('HOME_PATH')) {
    define('HOME_PATH', TMP_PATH);
}

// make home env var available (required for composer in post-install scripts)
putenv('HOME=' . HOME_PATH);

// make sure the script doesn't time out on large deployments
ignore_user_abort(true);
set_time_limit(0);

// include composer autoloader
require APPLICATION_PATH . '/vendor/autoload.php';
