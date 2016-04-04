<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry;


class Debug
{
    public static function prd($data, $die = true)
    {
        if (!self::isCommandLineInterface()) {
            echo "<pre>";
        }
        print_r($data);
        if (!self::isCommandLineInterface()) {
            echo "</pre>";
        }
        if ($die) {
            die();
        }
    }

    public static function isCommandLineInterface()
    {
        return (php_sapi_name() === 'cli');
    }
}