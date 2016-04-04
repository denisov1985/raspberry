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

use Raspberry\Exception\View\TemplateNotFoundException;

class View
{
    private $template;
    private $layout;
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function render($data)
    {
        if (!file_exists($this->_getTemplatePath())) {
            throw new TemplateNotFoundException("Template not found");
        }
        $template = new Template($this, $data);
        $content = $template->render();
        $layout = new Layout($this, [
            'content' => $content
        ]);

        return $layout->render();
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    private function _getTemplatePath() {
        return realpath('..') . sprintf('/Application/Views/%s/%s.phtml', $this->router->getController(), $this->router->getAction());
    }

    private function _getLayoutPath() {
        return realpath('..') . '/Application/Views/layout.phtml';
    }

}