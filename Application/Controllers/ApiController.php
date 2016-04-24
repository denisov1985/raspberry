<?php

/**
 * Created by PhpStorm.
 * User: denis
 * Date: 23.04.2016
 * Time: 11:36
 */
class ApiController extends \Raspberry\Controller
{

    public function __construct()
    {
        $this->setContentType(self::CONTENT_JSON);
    }

    public function getPublicationsAction()
    {
        $publications = PostsModel::findBy([
            'post_is_published' => 0,
            'group_id' => 1,
            'post_date_time >' => date('Y-m-d H:i:s')
        ], ['order' => 'post_date_time', 'sort' => 'ASC'], 1);

        $result = [];
        foreach ($publications as $p) {
            $p->setPostName(urlencode($p->getPostName()));
            $p->setPostContent(urlencode($p->getPostContent() . PHP_EOL . $p->getPostLink()));

            $date = date('m/d/Y', strtotime($p->getPostDateTime()));
            $time = date('H', strtotime($p->getPostDateTime())) * 60 * 60;;
            $time = $time + date('i', strtotime($p->getPostDateTime())) * 60;
            $time = $time + date('s', strtotime($p->getPostDateTime()));

            $p->setPostDate(urlencode($date));
            $p->setPostTime(urlencode($time));

            $result[] = $p->get();
        }

        if (!empty($result)) {
            $result = array_pop($result);
        }

        return $result;
    }

    public function photosAction()
    {
        $file = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/Test/photos.json');
        return json_decode($file, true);
    }

}