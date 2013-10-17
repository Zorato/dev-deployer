<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/Slim_Framework_TestCase.php';

// define some paths
define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
define('TMP_PATH', realpath(APPLICATION_PATH . '/tmp'));
define('HOME_PATH', realpath(TMP_PATH . '/home'));
define('FIXTURE_PATH', APPLICATION_PATH . '/tests/fixtures');

// make home env var available (required for composer in post-install scripts)
putenv('HOME=' . HOME_PATH);

