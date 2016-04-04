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


class Template extends Container
{
    private $content;

    public function __construct(View $view, $data)
    {
        parent::__construct($data);
        ob_start();
        include $this->_getPath($view);
        $this->content = ob_get_clean();
    }

    protected function _getPath(View $view) {
        return realpath('..') . sprintf('/Application/Views/%s/%s.phtml', $view->getRouter()->getController(), $view->getRouter()->getAction());
    }

    public function render()
    {
        return $this->content;
    }

}