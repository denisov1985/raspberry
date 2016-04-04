<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry\Config;

/**
 * Config in json format
 * Class JsonConfig
 * @package Raspberry
 */
class JsonConfig extends Config
{
    /**
     * Get config from JsonFile
     * JsonConfig constructor.
     * @param $file
     */
    public function __construct($file)
    {
        if (!file_exists($file)) {
            throw new \Exception("Config file $file not found");
        }
        $content = file_get_contents($file);
        $data = json_decode($content, true);
        parent::__construct($data);
    }

}