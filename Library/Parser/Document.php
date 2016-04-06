<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Parser;

require_once 'ext/HTMLPurifier/HTMLPurifier.auto.php';
require_once 'ext/simple_html_dom.php';

class Document
{
    private $dom;

    public function __construct($content)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        $clean_html = $purifier->purify($content);
        $this->dom = str_get_html($clean_html);
    }

    public function get($query)
    {
        return $this->dom->find($query);
    }

    public static function parseUrl($url)
    {
        parse_str(html_entity_decode($url), $data);
        return $data;
    }
}