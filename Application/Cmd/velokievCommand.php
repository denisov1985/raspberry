<?php

use Raspberry\Cmd\Command;
use Parser\Parser;
use Parser\Document;

class VelokievCommand extends Command
{

    private $db;
    const LIST_URL = 'http://velokyiv.com/forum/viewforum.php?f=1';

    public function run()
    {
        $parser = new Parser();
        $document = $parser->getDocument(self::LIST_URL);
        $topicLinks = $document->get('.topictitle');

        //echo count($topicLinks); die();

        foreach ($topicLinks as $topic) {
            /** @var phpQueryObject $row */

            $row = $topic->parents('li');
            $class =$row->attr('class');
            if (substr_count($class, 'announce') > 0 || substr_count($class, 'sticky')) {
                continue;
            }
            $href = $topic->attr('href');
            $href = str_ireplace('./viewtopic.php', 'http://velokyiv.com/forum/viewtopic.php', $href);
            $href = preg_replace('/&?sid=[^&]*/', '', $href);

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
            $post->save();
        }

        $posts = PostsModel::findBy(['post_is_published' => '-1']);
        foreach ($posts as $post) {
            $document = $parser->getDocument($post->getPostLink());
            $content  = $document->get('.content');
            $content = $content[0]->html();
            $content = str_ireplace('</p>', PHP_EOL, $content);
            $content = str_ireplace('<br>', PHP_EOL, $content);
            $content = str_ireplace('</br>', PHP_EOL, $content);
            $content =strip_tags($content);
            $content = explode('Реєстрація', $content);
            $content = $content[0];

            $date = explode(' ', $post->getPostName());
            $date = $date[0];
            $date = explode('.', $date);
            $date = $date[2] . '-' . $date[1] . '-' . $date[0];
            $time = explode('Старт: ', $content);
            $time = explode(PHP_EOL, $time[1]);
            $time = trim($time[0]);
            $dateTime = $date . ' ' . $time . ':00';

            $post->setPostIsPublished('0');
            $post->setPostDateTime($dateTime);
            $post->setPostContent($content);
            $post->save();
            echo ".";
        }


    }

    private function clearLink() {

    }

}