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
use Raspberry\Http\Response;
use Raspberry\Http\Request;
use Raspberry\Router\ErrorRouter;

class Kernel
{
    private $di;
    private $request;
    private $response;
    private $router;
    private $view;
    private $data;

    public function __construct(DependencyInjection $di)
    {
        $this->di = $di;
        $bootstrap = new Bootstrap($di);
        unset($bootstrap);
    }

    /**
     * Handle request
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {

        $this->router = new Router($request);
        $this->view   = new View($this->router, $this->di);
        $this->data   = $this->router->invoke();
        $content = $this->view->render($this->data);
        return new Response($content);
    }

}