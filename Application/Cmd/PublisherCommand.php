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

        $a = $facebook->getPhotos();
        $arr = $a->getDecodedBody()['data'];

        $posts = PostsModel::findBy(['post_is_published' => '1'], ['order' => 'post_date_time']);
        foreach ($posts as $post) {
            // POST IN DEBUG GROUP
            $facebook->setTarget('246669462349739');
            $response = $facebook->post($post->getPostLink(), $post->getPostName(), $post->getPostContent(), 'velokyiv.com', 'Велопокатушки ( покатеньки), велопоходи, туризм.');
            $body = $response->getDecodedBody();
            $id = $body['id'];
            // GET POST CURRENT
            $response = $facebook->getPost($id);
            $body = $response->getDecodedBody();
            $img = file_get_contents($body['picture']);
            var_dump(strlen($img));
            if (strlen($img) < 100) {
                $x = array_rand($arr);
                $picture = $arr[$x]['images'][0]['source'];
            }   else  {
                $picture = '';
            }

            $facebook->setTarget('208585119523052');
            $response = $facebook->post($post->getPostLink(), $post->getPostName(), $post->getPostContent(), 'velokyiv.com', 'Велопокатушки ( покатеньки), велопоходи, туризм.', $picture);

            echo 'Posted with id: ' . ' --- ' . $post->getPostName() . PHP_EOL;
            $post->setPostIsPublished('1');
            $post->save();
            sleep(5);
        }

        die('DONE');
        //$response = $facebook->post($post->getPostLink(), $post->getPostName());



    }
}