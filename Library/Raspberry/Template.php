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
    private $scripts;
    private $css;

    public function __construct(View $view, $data)
    {
        parent::__construct($data);
        $assets = $view->getDi()->get('application.assets_manager');
        $this->scripts = $assets->getJavaScripts();
        $this->css     = $assets->getCss();
        ob_start();
        if (!file_exists($this->_getPath($view))) {
            throw new \Exception('Template not found in: ' . $this->_getPath($view));
        }
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

    public function get($key, $default)
    {
        try {
            return $this->$key;
        }   catch (\Exception $e) {
            return $default;
        }
    }

}