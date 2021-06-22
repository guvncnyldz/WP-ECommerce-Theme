<?php

function set_config_google_login()
{
    require_once get_template_directory() . "/vendor/autoload.php";

    $google_client = new Google_Client();
    $google_client->setClientId(SocialConfig::$google_client_id);
    $google_client->setClientSecret(SocialConfig::$google_client_secret);

    $google_client->setRedirectUri(SocialConfig::$redirect_uri);
    $google_client->addScope("email");
    $google_client->addScope("profile");

    session_start();

    return $google_client;
}
