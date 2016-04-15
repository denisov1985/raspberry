<?php

define('ROOT_DIR', realpath('..'));
define('APP_DIR', realpath('../Application'));

$loader = require realpath('../Library/Raspberry') . DIRECTORY_SEPARATOR . 'Autoload.php';
require_once ROOT_DIR . '/vendor/autoload.php';
$loader->registerDirs([
    APP_DIR . '/Cmd',
    APP_DIR . '/Models',

]);
$loader->register();
use Raspberry\Cli;
$cli = new Cli($argv);
$cli->run();

file_put_contents('logs/' . $argv[1] . '.log', 'Last run: ' . date('Y-m-d H:i:s'));