<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

use Raspberry\Cmd\Command;

class TestCommand extends Command
{
    public function run()
    {
        $db = $this->getDi()->get('application.database');

        $result = $db->select();
    }

}