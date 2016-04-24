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
    private $di;

    public function __construct(Router $router, DependencyInjection $di)
    {
        $this->router = $router;
        $this->di     = $di;
    }

    public function render($data)
    {
        $contentType = $data['content_type'];
        unset($data['content_type']);

        if ($contentType == Controller::CONTENT_JSON) {
            header('Content-Type: application/json');
            return json_encode($data);
        }

        $template = new Template($this, $data);
        $content = $template->render();
        $data['content'] = $content;
        $layout = new Layout($this, $data);

        return $layout->render();
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }



}