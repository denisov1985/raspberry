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


class Layout extends Template
{
    protected function _getPath(View $view) {
        return realpath('..') . '/Application/Views/layout.phtml';
    }

    public function getContent()
    {
        return $this->content;
    }
}