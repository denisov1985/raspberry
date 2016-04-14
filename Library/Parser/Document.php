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

use Raspberry\Debug;

require_once 'ext/HTMLPurifier/HTMLPurifier.auto.php';
require_once 'ext/simple_html_dom.php';
require_once ('ext/phpQuery.php');

class Document
{
    private $dom;
    private $content;

    public function __construct($content)
    {
        $config = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($config);
        //$clean_html = $purifier->purify($content);
        $clean_html = $content;
        $this->content = $clean_html;

    }

    /**
     * @param $query
     * @return array
     */
    public function get($query)
    {
        $document = \phpQuery::newDocument($this->content);
        $hentry = $document->find($query);
        $data = [];
        foreach ($hentry as $el) {
            $pq = pq($el); // Это аналог $ в jQuery
            $data[] = $pq;
        }
        return $data;
    }

    public static function parseUrl($url)
    {
        parse_str(html_entity_decode($url), $data);
        return $data;
    }
}