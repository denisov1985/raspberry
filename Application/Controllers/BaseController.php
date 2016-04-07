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

abstract class BaseController extends Controller
{
    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->setView('title', $title);
    }


}