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

            $post = new PostsModel();
            $post->setPostName($topic->text());
            $post->setPostLink($href);
            $post->setPostImage('');
            $post->setPostDateTime('');
            $post->setPostIsPublished('-1');
            $post->setPostType('1');
            $post->setGroupId('2');
            $post->save();
        }

    }

    private function clearLink() {

    }

}