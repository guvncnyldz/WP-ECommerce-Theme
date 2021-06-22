<?php

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\Facebook\Exception\SDKException;

require_once get_template_directory() . "/vendor/autoload.php";

function get_facebook_login_url()
{
    session_start();
    $fb = new Facebook([
        'app_id' => SocialConfig::$facebook_app_id,
        'app_secret' => SocialConfig::$facebook_app_secret,
        'default_graph_version' => 'v2.10',
        //'default_access_token' => '{access-token}', // optional
    ]);

    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email'];
    $login_url = $helper->getLoginUrl(SocialConfig::$redirect_uri, $permissions);

    return $login_url;
}

function get_facebook_user()
{
    $fb = new Facebook([
        'app_id' => SocialConfig::$facebook_app_id,
        'app_secret' => SocialConfig::$facebook_app_secret,
        'default_graph_version' => 'v2.10',
        //'default_access_token' => '{access-token}', // optional
    ]);

    $helper = $fb->getRedirectLoginHelper();

    try {
        $accessToken = $helper->getAccessToken(SocialConfig::$redirect_uri);
    } catch(Facebook\Exception\ResponseException $e) {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exception\SDKException $e) {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    if (!isset($accessToken)) {
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

    try {
        // Returns a `Facebook\Response` object
        $response = $fb->get('/me?fields=id,name,first_name,email,last_name', $accessToken->getValue());
    } catch(Facebook\Exception\ResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exception\SDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    } catch (FacebookSDKException $e) {
        echo 'Facebooksdkexception: ' . $e->getMessage();
    }

    $user = $response->getGraphUser();

    return $user;
}
