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

        $fb = new Facebook\Facebook([
            'app_id' => '1590372557949606', // Replace {app-id} with your app id
            'app_secret' => 'a0318f2d84ef48b57a06a008859b87d7',
            'default_graph_version' => 'v2.5',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        $permissions = []; // Optional permissions
        $permissions[] = 'public_profile';
        $permissions[] = 'user_friends';
        $permissions[] = 'email';
        $permissions[] = 'user_about_me';
        $permissions[] = 'user_actions.books';
        $permissions[] = 'user_actions.fitness';
        $permissions[] = 'user_actions.music';
        $permissions[] = 'user_actions.news';
        $permissions[] = 'user_actions.video';
        $permissions[] = 'user_birthday';
        $permissions[] = 'user_education_history';
        $permissions[] = 'user_events';
        $permissions[] = 'user_games_activity';
        $permissions[] = 'user_hometown';
        $permissions[] = 'user_likes';
        $permissions[] = 'user_location';
        $permissions[] = 'user_managed_groups';
        $permissions[] = 'user_photos';
        $permissions[] = 'user_posts';
        $permissions[] = 'user_relationships';
        $permissions[] = 'user_relationship_details';
        $permissions[] = 'user_religion_politics';
        $permissions[] = 'user_tagged_places';
        $permissions[] = 'user_videos';
        $permissions[] = 'user_website';
        $permissions[] = 'user_work_history';
        $permissions[] = 'read_custom_friendlists';
        $permissions[] = 'read_insights';
        $permissions[] = 'read_audience_network_insights';
        $permissions[] = 'read_page_mailboxes';
        $permissions[] = 'manage_pages';
        $permissions[] = 'publish_pages';
        $permissions[] = 'publish_actions';
        $permissions[] = 'rsvp_event';
        $permissions[] = 'pages_show_list';
        $permissions[] = 'pages_manage_cta';
        $permissions[] = 'pages_manage_instant_articles';
        $permissions[] = 'ads_read';
        $permissions[] = 'ads_management';
        $loginUrl = $helper->getLoginUrl('http://localhost/index/callback', $permissions);

        $fb->setDefaultAccessToken($_SESSION['fb_access_token']);


        $a = $fb->get('/1135946616435982/photos?limit=200');
        $arr = $a->getDecodedBody()['data'];
        $x = array_rand($arr);

        $a = $fb->get('/' . $arr[$x]['id'] . '?fields=images');
        $arr = $a->getDecodedBody();
        $img = $arr['images'][0]['source'];

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