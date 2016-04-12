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

        $db = $this->getDi()->get('application.database');
        $apps = $db->select('app', ['name' => $appName]);
        $app = array_pop($apps);
        $facebook = new Api\Facebook\Client($app);

        $token = $facebook->getAccessToken();
        $app->app_token = $token;

        $this->flush($app);
        header('Location: http://localhost/');
        die();
    }

    public function debugAction()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
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

        echo "ok";
        die();
    }
}