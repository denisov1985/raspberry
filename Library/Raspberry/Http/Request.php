<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry\Http;

use Raspberry\Debug;

class Request
{
    private $get;
    private $post;
    private $cookies;
    private $server;
    private $files;
    private $headers;

    public function __construct()
    {
        $this->get     = new \Raspberry\Container($_GET);
        $this->post    = new \Raspberry\Container($_POST);
        $this->cookies = new \Raspberry\Container($_COOKIE);
        $this->server  = new \Raspberry\Container($_SERVER);
        $this->files   = new \Raspberry\Container($_FILES);
        $this->headers = getallheaders();
    }

    public function setError(\Exception $e)
    {
        $this->get->_url = '/error/index';

        $data = [];
        foreach ($e->getTrace() as $key => $error) {

            $data[$key]['file'] = @$error['file'];
            $data[$key]['line'] = @$error['line'];
            $data[$key]['function'] = @$error['function'];
            $data[$key]['class'] = @$error['class'];
        }
        $this->post->_error = [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => $data,
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ];
    }

    public function getError()
    {
        return $this->post->_error;
    }

    /**
     * @return array|false
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return \Raspberry\Container
     */
    public function get($name = null, $default = null)
    {
        if (isset($name)) {
            try {
                $result = $this->get->$name;
            } catch (\Exception $e) {
                $result = $default;
            }
            return $result;
        }
        return $this->get;
    }

    /**
     * @return \Raspberry\Container
     */
    public function post()
    {
        return $this->post;
    }

    /**
     * @return \Raspberry\Container
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * @return \Raspberry\Container
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return \Raspberry\Container
     */
    public function getFiles()
    {
        return $this->files;
    }


}