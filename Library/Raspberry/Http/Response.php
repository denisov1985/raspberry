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


class Response
{
    const HTTP_OK             = 200;
    const HTTP_NOT_FOUND      = 404;
    const HTTP_FORBIDDEN      = 403;
    const HTTP_INTERNAL_ERROR = 503;

    private $content;
    private $headers;
    private $cookies;
    private $code;

    public function __construct($content, $headers = [], $code = self::HTTP_OK, $cookies = [])
    {
        $this->content = $content;
        $this->headers = $headers;
        $this->cookies = $cookies;
        $this->code    = $code;
    }

    public function send()
    {
        $this->_sendCode();
        $this->_sendHeaders();
        $this->_sendCookies();
        $this->_sendContent();
    }

    private function _sendContent() {
        echo $this->content;
    }

    private function _sendHeaders() {
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }
    }

    private function _sendCode() {
        http_response_code($this->code);
    }

    private function _sendCookies() {
        // @TODO implement
    }
}