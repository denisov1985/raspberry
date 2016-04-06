<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

use Raspberry\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return [
            'ololo' => 'trololo'
        ];
    }

    public function debugAction()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        print_r($headers);

        die();
    }
}