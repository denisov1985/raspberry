<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry\Router;

use Raspberry\Http\Request;
use Raspberry\Router;

class ErrorRouter extends Router
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->setController('error');
        $this->setAction('index');
    }
}