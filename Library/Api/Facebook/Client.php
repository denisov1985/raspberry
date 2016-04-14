<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Api\Facebook;

use Facebook\Facebook;

class Client
{
    private $fb;
    private $settings;
    private $target;

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    public function __construct($data)
    {
        $this->settings = $data;
        $this->fb = new Facebook([
            'app_id' => $data['app_id'],
            'app_secret' => $data['app_secret'],
            'default_graph_version' => 'v2.5',
        ]);
        $this->fb->setDefaultAccessToken($data['app_token']);
    }

    public function post($url, $name ='', $message = '', $caption = '', $description = '')
    {
        $linkData = [
            'link' => $url,
            'message' => $message,
        ];

        // Returns a `Facebook\FacebookResponse` object
        $response = $this->fb->post('/' . $this->target . '/feed', $linkData);
        return $response;
    }

    public function getLoginUrl()
    {
        $helper = $this->fb->getRedirectLoginHelper();
        return $helper->getLoginUrl('http://localhost/index/callback/' . $this->settings['name'], Permissions::get());
    }

    public function getAccessToken()
    {
        $helper = $this->fb->getRedirectLoginHelper();
        $accessToken = $helper->getAccessToken();
        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $this->fb->getOAuth2Client();
        // Get the access token metadata from /debug_token
        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            var_dump($accessToken);
            var_dump($accessToken->getValue());
        }
        return $accessToken->getValue();
    }
}