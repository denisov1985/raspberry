<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry\Assets;

use Raspberry\Config\Config;
use Raspberry\Debug;

class AssetsManager
{
    private $config;
    private $scripts;
    private $css;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getJavaScripts()
    {
        if (isset($this->scripts)) {
            return $this->scripts;
        }
        $this->loadArray($this->config->js, $this->scripts);
        return $this->scripts;
    }

    public function getCss()
    {
        if (isset($this->css)) {
            return $this->css;
        }
        $this->loadArray($this->config->css, $this->css);
        return $this->css;
    }

    public function loadArray($collection, &$data)
    {
        $scripts = [];
        if ($collection instanceof \Traversable) {
            foreach ($collection as $key => $value) {
                if ('directory_prefix' === $key) {
                    $prefix = $value;
                    continue;
                }
                $scripts[] = $this->loadArray($value, $data);
            }
        }   else  {

            $dir = realpath('..') . $collection;
            if(is_dir($dir)) {
                $this->scanFiles($dir, $collection, $data);
            }   else  {
                $scripts[] = $collection;
                $data[] = str_ireplace('public/', '', $collection);
            }
        }
        return $scripts;
    }



    public function scanFiles($dir, $localPath, &$data)
    {
        $result = [];
        $items = scandir($dir);
        unset($items[0]);
        unset($items[1]);
        foreach ($items as $item) {
            if (is_dir($dir . '/' . $item)) {
                $result[] = $this->scanFiles($dir . '/' . $item, $localPath, $data);
            }   else  {
                $result[] = $item;
                $data[] = str_ireplace(str_ireplace('\\', '/', realpath('.')), '', str_ireplace('\\', '/', $dir) . '/' . $item);
            }
        }
        return $result;
    }
}