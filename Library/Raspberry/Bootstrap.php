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


class Bootstrap
{
    private $di;

    public function __construct(DependencyInjection $di)
    {
        $this->di = $di;
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
}