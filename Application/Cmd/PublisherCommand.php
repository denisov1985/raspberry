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

        // 143431279390736

        $groups = GroupsModel::findAll();
        $groupsData = [];
        foreach ($groups as $group) {
            $groupsData[$group->getId()] = $group;
        }

        $posts = PostsModel::findBy(['post_is_published' => '0'], ['order' => 'post_date_time']);
        if (count($posts) == 0) {
            echo "No new posts" . PHP_EOL;
            die();
        }

        $app = AppModel::find(5);
        $facebook = new \Api\Facebook\Client($app);

        $arr = [];
        $a = $facebook->get('/136787640055100/photos?limit=200&fields=images');
        $arr[1] = $a->getDecodedBody()['data'];
        $a = $facebook->get('/143431279390736/photos?limit=200&fields=images');
        $arr[2] = $a->getDecodedBody()['data'];

        foreach ($posts as $post) {
            // POST IN DEBUG GROUP
            $facebook->setTarget('1745943002320945');
            $response = $facebook->post($post->getPostLink(), $post->getPostName(), $post->getPostContent(), 'velokyiv.com', 'Велопокатушки ( покатеньки), велопоходи, туризм.');
            $body = $response->getDecodedBody();

            $groupId = $post->getGroupId();

            $id = $body['id'];
            sleep(3);
            // GET POST CURRENT
            $response = $facebook->getPost($id);
            $body = $response->getDecodedBody();
            $img = file_get_contents($body['picture']);
            var_dump(strlen($img));
            if (strlen($img) < 4000) {
                $x = array_rand($arr[$groupId]);
                $picture = $arr[$groupId][$x]['images'][0]['source'];
            }   else  {
                $picture = '';
            }

            $facebook->setTarget($groupsData[$groupId]->getExtId());
            $response = $facebook->post($post->getPostLink(), $post->getPostName(), $post->getPostContent(), '', '', $picture);

            echo 'Posted with id: ' . ' --- ' . $post->getPostName() . PHP_EOL;
            $post->setPostIsPublished('1');
            $post->save();
            sleep(2);
        }

        die('DONE');
        //$response = $facebook->post($post->getPostLink(), $post->getPostName());



    }
}