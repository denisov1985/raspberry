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

        $page = rand(1, 2);

        for ($i = 1; $i < 5; $i++) {
            $content = file_get_contents('https://api.flickr.com/services/rest?extras=can_addmeta%2Ccan_comment%2Ccan_download%2Ccan_share%2Ccontact%2Ccount_comments%2Ccount_faves%2Ccount_views%2Cdate_taken%2Cdate_upload%2Cdescription%2Cicon_urls_deep%2Cisfavorite%2Cispro%2Clicense%2Cmedia%2Cneeds_interstitial%2Cowner_name%2Cowner_datecreate%2Cpath_alias%2Crealname%2Crotation%2Csafety_level%2Csecret_k%2Csecret_h%2Curl_c%2Curl_f%2Curl_h%2Curl_k%2Curl_l%2Curl_m%2Curl_n%2Curl_o%2Curl_q%2Curl_s%2Curl_sq%2Curl_t%2Curl_z%2Cvisibility%2Cvisibility_source%2Co_dims%2Cis_marketplace_printable%2Cis_marketplace_licensable%2Cpubliceditability&per_page=25&page=' . $page . '&get_user_info=1&primary_photo_extras=url_c%2C%20url_h%2C%20url_k%2C%20url_l%2C%20url_m%2C%20url_n%2C%20url_o%2C%20url_q%2C%20url_s%2C%20url_sq%2C%20url_t%2C%20url_z%2C%20needs_interstitial%2C%20can_share&jump_to=&photoset_id=72157666881826205&viewerNSID=&method=flickr.photosets.getPhotos&csrf=&api_key=4fc84fdfb27b9da757867afc53daed4e&format=json&hermes=1&hermesClient=1&reqId=24a28c81&nojsoncallback=1');
            $photos = json_decode($content, true);

            //Raspberry\Debug::prd($photos); die();
            $img = @$photos['photoset']['photo'][rand(0, count($photos['photoset']['photo']) - 1)]['url_l_cdn'];
            if ($img) {
                break;
            }
        }

        echo "<pre>";
        print_r($photos['photoset']['photo'][rand(0, count($photos['photoset']['photo']) - 1)]);


        $fb = new Facebook\Facebook([
            'app_id' => '1590372557949606', // Replace {app-id} with your app id
            'app_secret' => 'a0318f2d84ef48b57a06a008859b87d7',
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl('http://localhost/index/callback', $permissions);

        $fb->setDefaultAccessToken($_SESSION['fb_access_token']);

        $linkData = [
            'link' => 'http://velokyiv.com/forum/viewtopic.php?f=1&t=160940',
            'message' => 'Facebook API test',
            'picture' => $img,
            'caption' => 'Test message',
            'description' => 'Test description',
            'name' => 'Покатушка в Воскресенье - Гранитный Карьер Ul@senko'
        ];

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->post('/1133338330030144/feed', $linkData);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $graphNode = $response->getGraphNode();

        echo 'Posted with id: ' . $graphNode['id'];

        die();

        return [
            'loginUrl' => htmlspecialchars($loginUrl)
        ];
    }

    public function callbackAction()
    {
        $fb = new Facebook\Facebook([
            'app_id' => '1590372557949606', // Replace {app-id} with your app id
            'app_secret' => 'a0318f2d84ef48b57a06a008859b87d7',
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        echo '<h3>Access Token</h3>';
        echo "<pre>";
        var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        echo '<h3>Metadata</h3>';
        var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId('1590372557949606'); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

                var_dump($accessToken);
                die();

            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

            echo '<h3>Long-lived</h3>';
            var_dump($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;
        die();
    }

    public function debugAction()
    {
        $request = $this->getRequest();
        $headers = $request->getHeaders();
        print_r($headers);

        die();
    }
}