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

        $posts = PostsModel::findBy(['post_is_published' => '0'], ['order' => 'post_date_time']);
        if (count($posts) == 0) {
            echo "No new posts" . PHP_EOL;
            die();
        }

        $app = AppModel::find(5);
        $facebook = new \Api\Facebook\Client($app);

        $a = $facebook->get('/136787640055100/photos?limit=200&fields=images');
        $arr = $a->getDecodedBody()['data'];

        foreach ($posts as $post) {
            // POST IN DEBUG GROUP
            $facebook->setTarget('1745943002320945');
            $response = $facebook->post($post->getPostLink(), $post->getPostName(), $post->getPostContent(), 'velokyiv.com', 'Велопокатушки ( покатеньки), велопоходи, туризм.');
            $body = $response->getDecodedBody();

            $id = $body['id'];
            sleep(3);
            // GET POST CURRENT
            $response = $facebook->getPost($id);
            $body = $response->getDecodedBody();
            $img = file_get_contents($body['picture']);
            var_dump(strlen($img));
            if (strlen($img) < 2000) {
                $x = array_rand($arr);
                $picture = $arr[$x]['images'][0]['source'];
            }   else  {
                $picture = '';
            }

            echo PHP_EOL . @$arr[$x]['images'][0]['source'] . PHP_EOL;

            $facebook->setTarget('1346688292014965');
            $response = $facebook->post($post->getPostLink(), $post->getPostName(), $post->getPostContent(), 'velokyiv.com', 'Велопокатушки ( покатеньки), велопоходи, туризм.', $picture);

            echo 'Posted with id: ' . ' --- ' . $post->getPostName() . PHP_EOL;
            $post->setPostIsPublished('1');
            $post->save();
            sleep(2);
        }

        die('DONE');
        //$response = $facebook->post($post->getPostLink(), $post->getPostName());



    }
}