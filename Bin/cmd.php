<?php

define('ROOT_DIR', realpath('..'));
define('APP_DIR', realpath('../Application'));

$loader = require realpath('../Library/Raspberry') . DIRECTORY_SEPARATOR . 'Autoload.php';
require_once ROOT_DIR . '/vendor/autoload.php';
$loader->registerDirs([
    APP_DIR . '/Cmd',

]);
$loader->register();
use Raspberry\Cli;
$cli = new Cli($argv);
$cli->run();

$fb = new Facebook\Facebook([
    'app_id' => '1590372557949606',
    'app_secret' => 'a0318f2d84ef48b57a06a008859b87d7',
    'default_graph_version' => 'v2.5',
]);

var_dump($fb);