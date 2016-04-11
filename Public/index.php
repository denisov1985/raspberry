<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

use Raspberry\Kernel;
use Raspberry\Http\Request;
use Raspberry\DependencyInjection;
session_start();

define('ROOT_DIR', realpath('..'));
define('APP_DIR', realpath('../Application'));

/** @var Raspberry\Autoload $loader */
$loader = require_once realpath('../Library/Raspberry/Autoload.php');
$loader->register();

$loader->registerDirs([
    APP_DIR . '/Controllers',
    APP_DIR . '/Models'
]);
require_once ROOT_DIR . '/vendor/autoload.php';
$kernel   = new Kernel(new DependencyInjection());
$request  = new Request();
$response = $kernel->handle($request);
$response->send();

