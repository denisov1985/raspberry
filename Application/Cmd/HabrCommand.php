<?php

use Raspberry\Cmd\Command;
use Parser\Parser;
use Parser\Document;

class HabrCommand extends Command
{

    private $db;

    public function run()
    {
        $parser = new Parser();
        $document = $parser->getDocument('https://habrahabr.ru/hub/php/');
        $topicLinks = $document->get('.shortcuts_item');


        foreach ($topicLinks as $topic) {
            /** @var phpQueryObject $topic */
            $link = $topic->find('.post_title');

            $href = $link->attr('href');
            $post = PostsModel::findBy(['post_link' => $href]);
            if ($post) {
                continue;
            }

            if ($link->text() == '' || $href == '') {
                continue;
            }

            $post = new PostsModel();
            $post->setPostName($link->text());
            $post->setPostLink($href);
            $post->setPostDateTime(date('Y-m-d H:i:s'));
            $post->setPostIsPublished('0');
            $post->setPostType('1');
            $post->setGroupId('2');
            $post->setPostContent($topic->find('.content')->text());
            $post->save();

            echo $link->text() . PHP_EOL;
        }

    }

    private function clearLink() {

    }

}