<?php

define('ROOT_DIR', realpath('..'));
define('APP_DIR', realpath('../Application'));

$loader = require realpath('../Library/Raspberry') . DIRECTORY_SEPARATOR . 'Autoload.php';
$loader->registerDirs([
    APP_DIR . '/Cmd'
]);
$loader->register();
use Raspberry\Cli;
$cli = new Cli($argv);
