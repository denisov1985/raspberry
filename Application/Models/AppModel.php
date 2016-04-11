<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

use Raspberry\Model;

class AppModel extends Model
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->scaffold = [
            'name' => [
                'type' => 'varchar',
                'size' => 255
            ],
            'app_id' => [
                'type' => 'varchar',
                'size' => 255
            ],
            'app_secret' => [
                'type' => 'varchar',
                'size' => 255
            ],
            'app_token' => [
                'type' => 'varchar',
                'size' => 255
            ],
        ];
    }
}