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


class Cli
{
    private $di;
    private $class;

    public function __construct($args)
    {
        $this->di = new DependencyInjection;
        $bootstrap = new Bootstrap($this->di);
        unset($bootstrap);
        $this->class = ucfirst($args[1]) . 'Command';
        $this->class = new $this->class();
        $this->class->setDi($this->di);
    }

    public function run()
    {
        $this->class->run();
    }
}