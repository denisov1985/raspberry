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
        try {
            $content = $this->_processRequest($request);
        }   catch (\Exception $e) {
            $request->setError($e);
            $content = $this->_processRequest($request);
        }
        return new Response($content);
    }

    private function _processRequest(Request $request)
    {
        $this->router = new Router($request);
        $this->view   = new View($this->router, $this->di);
        $this->data   = $this->router->invoke($this);
        return $this->view->render($this->data);
    }

    /**
     * @return DependencyInjection
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param DependencyInjection $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

}