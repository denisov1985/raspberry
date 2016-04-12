<?php

use Raspberry\Cmd\Command;
use Parser\Parser;
use Parser\Document;

/**
 * Class VelokievCommand//$fb->setDefaultAccessToken($_SESSION['fb_access_token']);
 *
 *
 * /*$a = $fb->get('/1135946616435982/photos?limit=200');
 * $arr = $a->getDecodedBody()['data'];
 * $x = array_rand($arr);
 *
 * $a = $fb->get('/' . $arr[$x]['id'] . '?fields=images');
 * $arr = $a->getDecodedBody();
 * $img = $arr['images'][0]['source'];
 *
 * $linkData = [
 * 'link' => 'http://velokyiv.com/forum/viewtopic.php?f=1&t=160940',
 * 'message' => 'Facebook API test',
 * 'picture' => $img,
 * 'caption' => 'Test message',
 * 'description' => 'Test description',
 * 'name' => 'Покатушка в Воскресенье - Гранитный Карьер Ul@senko'
 * ];
 *
 * try {
 * // Returns a `Facebook\FacebookResponse` object
 * $response = $fb->post('/1133338330030144/feed', $linkData);
 * } catch(Facebook\Exceptions\FacebookResponseException $e) {
 * echo 'Graph returned an error: ' . $e->getMessage();
 * exit;
 * } catch(Facebook\Exceptions\FacebookSDKException $e) {
 * echo 'Facebook SDK returned an error: ' . $e->getMessage();
 * exit;
 * }
 *
 * $graphNode = $response->getGraphNode();
 *
 * echo 'Posted with id: ' . $graphNode['id'];
 *
 * die();*/
class VelokievCommand extends Command
{

    private $db;

    public function run()
    {
        $this->prepare();
        $parser = new Parser();

        $link = 'http://velokyiv.com/forum/cycleplan.php?cp_forum_id=1';
        $document = $parser->getDocument($link);

        die('ok');

        echo $link . PHP_EOL;

        $rows = $document->get('table.cp_table tr');
        foreach ($rows as $row) {
            if ($row->find('td', 0) == NULL) {
                continue;
            }

            $day = trim($row->find('td', 0)->innertext);
            $time = trim($row->find('td', 1)->innertext);
            $title = trim(strip_tags($row->find('td', 2)->innertext));

            $link = $row->find('td a', 0)->href;
            $data = Document::parseUrl($link);
            $topicId = $data['cp_id'];

            $members = trim($row->find('td', 3)->innertext);
            $org = trim(strip_tags($row->find('td', 4)->innertext));

            try {

                $result = $this->db->select('topics', ['trip_id' => $topicId]);

                if (count($result) > 0) {
                    continue;
                }

                $data = [
                    'trip_name' => $title,
                    'user_forum_id' => 0,
                    'created_date' => sprintf('%s-%s-%s', date('Y'), date('m'), $day),
                    'user_name' => $org,
                    'members' => $members,
                    'is_shared' => 0,
                    'trip_id' => $topicId
                ];
                $this->db->insert('topics', $data);
            } catch (Exception $e) {
                print_r($data);
                die();
            }

        }
    }


    private function prepare()
    {
        /** @var Raspberry\Database\DatabaseAdapter $db */
        $this->db = $this->getDi()->get('application.database');

        $this->db->createTableIfNotExist('posts', [
            'post_name' => [
                'type' => 'varchar',
                'size' => 255
            ],
            'post_link' => [
                'type' => 'varchar',
                'size' => 255
            ],
            'post_image' => [
                'type' => 'varchar',
                'size' => 255
            ],
            'post_date_time' => [
                'type' => 'datetime'
            ],
            'post_is_published' => [
                'type' => 'int'
            ],
            'post_type' => [
                'type' => 'int'
            ],
        ]);

        $this->db->createTableIfNotExist('posts_types', [
            'post_type_name' => [
                'type' => 'varchar',
                'size' => 255
            ],
        ]);

        $entity = PostsTypesModel::find(1);
        if (!$entity) {
            $entity = new PostsTypesModel();
            $entity->setPostTypeName('Вело покатушки');
            $entity->save();
        }
    }

}