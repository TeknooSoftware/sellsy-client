<?php

defined('RUN_CLI_MODE')
    || define('RUN_CLI_MODE', true);

defined('PHPUNIT')
    || define('PHPUNIT', true);

ini_set('memory_limit', '64M');

date_default_timezone_set('UTC');

error_reporting(E_ALL | E_STRICT);

require_once __DIR__.'/../vendor/autoload.php';
