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
use Raspberry\DependencyInjection;

define('ROOT_DIR', realpath('..'));
define('APP_DIR', realpath('../Application'));

/** @var Raspberry\Autoload $loader */
$loader = require_once realpath('../Library/Raspberry/Autoload.php');
$loader->register();

$loader->registerDirs([
    APP_DIR . '/Controllers',
    APP_DIR . '/Models'
]);

$kernel = new Kernel(new DependencyInjection());

function prd($data) {
    print_r($data);
    die();
}


