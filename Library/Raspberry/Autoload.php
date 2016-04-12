<?php

namespace Raspberry;

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */
class Autoload
{
    private $dirs;

    /**
     * Register autoloader directory
     * @param array $dirs
     */
    public function registerDirs(array $dirs)
    {
        $this->dirs = $dirs;
    }

    /**
     * Register autoloader
     */
    public function register() {
        spl_autoload_register(__NAMESPACE__ .'\Autoload::load');
    }

    /**
     * Load class file by class name
     * @param $className
     */
    public function load($className)
    {
        if ($this->_loadByDirectory($className)) {
            return;
        }
    }

    /**
     * Load file by registered directory
     * @param $className
     * @return bool
     */
    private function _loadByDirectory($className) {
        $fileName = $className . '.php';
        $fileName = str_ireplace('\\', DIRECTORY_SEPARATOR, $fileName);
        $dirs     = $this->dirs;
        $dirs[]   = dirname(__DIR__);
        foreach ($dirs as $dir) {
            $filePath = realpath($dir) . DIRECTORY_SEPARATOR . $fileName;

            if (file_exists($filePath)) {
                return require_once $filePath;
            }
        }
        return false;
    }

}

return new Autoload();