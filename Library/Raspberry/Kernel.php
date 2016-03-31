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


class Kernel
{
    private $di;

    public function __construct(DependencyInjection $di)
    {
        $this->di = $di;
        $this->_initBootstrap();
    }

    private function _initBootstrap() {
        $class = new \ReflectionClass(__NAMESPACE__ . '\Bootstrap');
        $methods = $class->getMethods();
        $bootstrap = new Bootstrap($this->di);
        foreach ($methods as $method) {
            if (substr($method->name, 0, 4) == 'init') {
                $reflectionMethod = new \ReflectionMethod(__NAMESPACE__ . '\Bootstrap', $method->name);
                echo $reflectionMethod->invoke($bootstrap);
            }
        }
    }
}