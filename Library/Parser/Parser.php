<?php

namespace Parser;

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */
class Parser
{
    const HTTP_GET  = 'GET';
    const HTTP_POST = 'POST';

    public function getDocument($url, $data = [], $method = self::HTTP_GET, $headers = [], $cookies = [])
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if (self::HTTP_POST == $method) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        }
        $headers  = [];
        $headers[] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8';
        $headers[] = 'Accept-Encoding:gzip, deflate, sdch';
        $headers[] = 'Accept-Language:en-US,en;q=0.8';
        $headers[] = 'Cache-Control:max-age=0';
        $headers[] = 'Connection:keep-alive';
        $headers[] = 'Referer:http://google.com.ua/';
        $headers[] = 'Upgrade-Insecure-Requests:1';
        $headers[] = 'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec ($ch);
        curl_close ($ch);
        $uncompressed = @gzdecode($result);
        $document = ($uncompressed) ? $uncompressed : $result;
        return new Document($document);
    }
}