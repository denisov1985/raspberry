<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

use Raspberry\Controller;

class IndexController extends BaseController
{
    public function indexAction()
    {
        //\Raspberry\Debug::prd()
        $this->setTitle('Facebook API management');
        $apps = AppModel::findAll();
        foreach ($apps as $key => $app) {
            $facebook = new Api\Facebook\Client($app);
            $apps[$key]->setRedirectUrl($facebook->getLoginUrl());
        }

        return [
            'apps' => $apps
        ];
    }

    public function callbackAction($appName)
    {

        $app = AppModel::findBy(['name' => $appName]);
        $app = $app[0];

        $facebook = new Api\Facebook\Client($app);
        $token = $facebook->getAccessToken();

        echo $token;

        $app->setAppToken($token);
        $app->save();
        die();
        header('Location: http://localhost/');
        die();
    }

    public function debugAction()
    {
        die('ok');
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        echo "<pre>";
        print_r($headers);

        die();
    }

    public function loadAction()
    {
        $db = $this->getDi()->get('application.database');

        $data = [
            'ATLAS-1' => [
                'name'       => 'ATLAS-1',
                'app_id'     => '1590372557949606',
                'app_secret' => 'a0318f2d84ef48b57a06a008859b87d7',
                'app_token'  => ''
            ],
            'ATLAS-2' => [
                'name'       => 'ATLAS-2',
                'app_id'     => '1669516096633077',
                'app_secret' => '1cf08d2ace5dbb0a330d13afaa9194e8',
                'app_token'  => ''
            ],
            'ATLAS-3' => [
                'name'       => 'ATLAS-3',
                'app_id'     => '1689389657978745',
                'app_secret' => '8cb621f6e5723ba364aecfc367df1a0c',
                'app_token'  => ''
            ],
            'ATLAS-4' => [
                'name'       => 'ATLAS-4',
                'app_id'     => '1666462593592777',
                'app_secret' => 'da9adfac5de2e297816f72469d52e6c6',
                'app_token'  => ''
            ],
            'ATLAS-5' => [
                'name'       => 'ATLAS-5',
                'app_id'     => '1061657327223627',
                'app_secret' => '6f1493137f5456b555bad232f6c74866',
                'app_token'  => ''
            ],
        ];

        foreach ($data as $value) {
            $result = $db->select('app', ['name' => $value['name']]);
            if (count($result) > 0) {
                continue;
            }
            $model = new AppModel($value);
            $this->flush($model);
        }

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
            'post_content' => [
                'type' => 'text'
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

        echo "ok";
        die();
    }
}