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


use Raspberry\Http\Request;

class Router
{
    private $request;
    private $controller;
    private $action;
    private $arguments;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->_parse();
    }

    public function invoke(Kernel $kernel)
    {
        $controllerName = $this->_getControllerName();
        $controller = new $controllerName();
        $controller->setRequest($this->request);

        $data = call_user_func_array([
            $controller,
            $this->_getActionName()
        ], $this->arguments);

        $view = call_user_func_array([
            $controller,
            'getView'
        ], []);

        return array_merge($data, $view);
    }

    private function _getControllerName() {
        return sprintf('%sController', strtolower($this->controller));
    }

    private function _getActionName() {
        return sprintf('%sAction', strtolower($this->action));
    }

    private function _parse() {
        $url = $this->request->get('_url', '/index');
        $parts = explode('/', $url);
        $this->controller = $parts[1];
        if (count($parts) < 3) {
            $this->action = 'index';
        }   else  {
            $this->action = $parts[2];
        }
        $this->arguments = [];
        if (count($parts) > 3) {
            $partsCount = count($parts);
            for ($i = 3; $i < $partsCount; $i++) {
                $this->arguments[] = $parts[$i];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param mixed $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param mixed $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
    }

}