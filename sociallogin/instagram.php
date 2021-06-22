<?php

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\Facebook\Exception\SDKException;

require_once get_template_directory() . "/vendor/autoload.php";

function get_instagram_login_url()
{
    session_start();
    $fb = new Facebook([
        'app_id' => SocialConfig::$facebook_app_id,
        'app_secret' => SocialConfig::$instagram_api_secret,
    ]);

    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['instagram_graph_user_profile'];
    $login_url = $helper->getLoginUrl(SocialConfig::$redirect_uri, $permissions);

    return $login_url;
}

function get_instagram_user()
{

}