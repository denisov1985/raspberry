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

    public function __construct($data)
    {
        $this->settings = $data;
        $this->fb = new Facebook([
            'app_id' => $data['app_id'],
            'app_secret' => $data['app_secret'],
            'default_graph_version' => 'v2.5',
        ]);
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