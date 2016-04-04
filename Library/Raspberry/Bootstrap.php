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
use Raspberry\Config\JsonConfig;
use Raspberry\Database\DatabaseAdapter;

class Bootstrap
{
    private $di;
    private static $instance;

    public function __construct(DependencyInjection $di)
    {
        $this->di = $di;
        self::$instance = $this;
    }

    public function initProfiler() {

    }

    public function initConfig() {
        $this->di->set('application.config', function() {
            return new JsonConfig(realpath('..') . '/config.json');
        });
    }

    public function initDatabase()
    {
        $this->di->set('application.database', function() {
            return new DatabaseAdapter(
                $this->di->get('application.config')->database
            );
        });
    }

    public static function run(DependencyInjection $di)
    {
        $class = new \ReflectionClass(get_class());
        $methods = $class->getMethods();
        $bootstrap = new self($di);
        foreach ($methods as $method) {
            if (substr($method->name, 0, 4) == 'init') {
                $reflectionMethod = new \ReflectionMethod(__NAMESPACE__ . '\Bootstrap', $method->name);
                echo $reflectionMethod->invoke(self::$instance);
            }
        }
    }
}