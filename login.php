<head>
    <?php
/**
 * Template Name: Login Page
 */

if (is_user_logged_in()) {
    header('Location:' . home_url());
}
?>
    <title>Duvar Kağıdı Marketi</title>

</head>
<?php

use Facebook\Facebook;

require_once get_template_directory() . "/sociallogin/google.php";
require_once get_template_directory() . "/sociallogin/facebook.php";
require_once get_template_directory() . "/utils/login-register.php";

$facebook_login_url = get_facebook_login_url();
$google_client = set_config_google_login();

if (isset($_POST['user_registeration'])) {
    if (my_register($_POST)) {
        header('Location:' . home_url() . '/giris-yap');
    }
} else if (isset($_POST['user_login'])) {
    if (my_login($_POST)) {
        header('Location:' . home_url());
    }
} else if (isset($_GET['scope'])) {
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $google_client->setAccessToken($token['access_token']);
        $_SESSION['access-token'] = $token['access_token'];

        $google_service = new Google_Service_Oauth2($google_client);
        $data = $google_service->userinfo->get();
        $id = $data["id"];
        $user = get_user_by('login', $id);

        $user_data = array(
            'first_name' => $data['givenName'],
            'last_name' => $data['familyName'],
            'email' => $data['email'],
            'password' => $id,
        );

        if (!$user) {
            if (my_register($user_data, $id,true)) {
                $user = get_user_by('login', $id);

                wp_set_current_user($user->ID);
                wp_set_auth_cookie($user->ID);
                header('Location:' . home_url());
            }
        } else {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            header('Location:' . home_url());
        }
    } else {
        alert("Hatalı Token");
    }
} else if (isset($_GET['code'])) {
    require_once get_template_directory() . "/vendor/autoload.php";

    $fb_user = get_facebook_user();

    $id = $fb_user->getId();
    $user = get_user_by('login', $id);

    $user_data = array(
        'first_name' => $fb_user->getFirstName(),
        'last_name' => $fb_user->getLastName(),
        'email' => $fb_user->getEmail(),
        'password' => $fb_user->getId(),
    );

    if (!$user) {
        if (my_register($user_data, $id)) {
            $user = get_user_by('login', $id);

            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            update_user_meta($user->ID, "social-login", '1');
            header('Location:' . home_url());
        }
    } else {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
        header('Location:' . home_url());
    }
}

wp_head();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="<?php echo bloginfo('template_url') ?>/scss/main.css">
</head>
<body>
<div class="login-body">
    <div class="content">
        <!-- Logo -->
        <a href=<?php echo home_url(); ?>>
            <?php $logo = get_field('logo', get_option('page_on_front')); ?>
            <img class="logo" src="<?php echo $logo['siyah_logo'] ?>" alt="Logo">
        </a>

        <div class="forms">
            <ul class="tab-list" role="tablist">
                <li class="active">
                    <a href="#cont-tab1" role="tab" data-toggle="tab">Giriş Yap</a>
                </li>
                <li role="presentation">
                    <a href="#cont-tab2" role="tab" data-toggle="tab">Üye Ol</a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Login -->
                <div id="cont-tab1" role="tabpanel" class="tab-pane active">
                    <form action="/giris-yap" method="post">
                        <div class="input-group">
                            <label for="">E-posta Adresiniz</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="input-group">
                            <label for="">Şifreniz</label>
                            <input type="password" name="password" required>
                        </div>
                        <button class="ui-button--primary ui-button-size--large" type="submit" name="user_login">Giriş
                            Yap
                        </button>
                        <a class="login-link" href="<?php echo wp_lostpassword_url() ?>">Şifreni hatırlamıyor musun?</a>
                    </form>
                </div>
                <!-- Signup -->
                <div id="cont-tab2" role="tabpanel" class="tab-pane">
                    <form action="/kayit-ol" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="">Ad</label>
                                    <input maxlength="32" type="text" name="first_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <label for="">Soyad</label>
                                    <input maxlength="32" type="text" name="last_name" required>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <label for="">E-posta Adresiniz</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="input-group">
                            <label for="">Şifreniz</label>
                            <input maxlength="16" minlength="8" type="password" name="password" required>
                        </div>
                        <label class="control control--checkbox">
                            <span>Önemli kampanyalardan haberdar olmak için Rıza Metni<br>kapsamında elektronik ileti almak istiyorum.</span>
                            <input type="checkbox" name="newsletter" checked/>
                            <div class="control__indicator"></div>
                        </label>
                        <button class="ui-button--primary ui-button-size--large" type="submit"
                                name="user_registeration">Üye Ol
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <ul class="or">
            <li>
                <div class="line"></div>
            </li>
            <li><span>Sosyal hesap ile giriş yap</span></li>
            <li>
                <div class="line"></div>
            </li>
        </ul>
        <ul class="social-button">
            <li>
                <a class="ui-button-size--large ui-button--secondary-outline"
                   href="<?php echo $google_client->createAuthUrl() ?>">
                    <img src="<?php echo bloginfo('template_url') ?>/img/logo/social-google.svg" alt="">
                    <span>Google</span>
                </a>
            </li>
<!--            <li>
                <a class="ui-button-size--large ui-button--secondary-outline" href="https://api.instagram.com/oauth/authorize?client_id=848448719362532&redirect_uri=https://dkm.ekmekk.app/giris-yap&scope=user_profile,user_media&response_type=code">
                    <img src="<?php /*echo bloginfo('template_url') */?>/img/logo/instagram.svg" alt="">
                    <span>Instagram</span>
                </a>
            </li>-->
            <li>
                <a class="ui-button-size--large ui-button--secondary-outline" href="<?php echo $facebook_login_url ?>">
                    <img src="<?php echo bloginfo('template_url') ?>/img/logo/social-facebook.svg" alt="">
                    <span>Facebook</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Copyright -->
<div class="copyright">
    <span>Duvar Kağıdı Marketi © Copyright <?php echo date("Y"); ?> Duvar Kağıdı Marketi Tic. A.Ş. Her Hakkı Saklıdır.</span>
</div>
<!-- jQuery Import -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="node_modules/slick-carousel/slick/slick.min.js"></script>
<script src="<?php echo bloginfo('template_url') ?>/js/product-slider-min.js"></script>
</body>
</html>
<?php wp_footer() ?>

