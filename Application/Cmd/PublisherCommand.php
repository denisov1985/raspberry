<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */
class PublisherCommand extends \Raspberry\Cmd\Command
{

    public function run()
    {

        $app = AppModel::find(1);
        $facebook = new \Api\Facebook\Client($app);
        $facebook->setTarget('208585119523052');

        $post = PostsModel::findAll();
        $post = $post[2];

        $response = $facebook->post($post->getPostLink(), $post->getPostName());

        $graphNode = $response->getGraphNode();
        echo 'Posted with id: ' . $graphNode['id'] . PHP_EOL;
    }
}