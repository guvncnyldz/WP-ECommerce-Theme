<?php
if (is_user_logged_in()) {
    header('Location:' . home_url());
} else {
    $user_login = $_GET['login'];
    $user_key = $_GET["key"];
    $check_key = check_password_reset_key($user_key, $user_login);
    if ($check_key->errors) {
        header('Location:' . home_url());
    }

    wp_head();

    if (isset($_GET['reset_password'])) {
        global $reg_errors;
        $reg_errors = new WP_Error;
        $new_password = $_GET['new_password'];
        $new_password_again = $_GET['new_password_again'];

        if (empty($new_password) || empty($new_password_again)) {
            $reg_errors->add('field', 'Required form field is missing');
        }

        if ($new_password != $new_password_again) {
            $reg_errors->add('passwords not same', 'Passwords are not same!');
        }

        if (is_wp_error($reg_errors)) {
            foreach ($reg_errors->get_error_messages() as $error) {
                $signUpError = '<p style="color:#FF0000; text-aling:left;"><strong>ERROR</strong>: ' . $error . '<br /></p>';
                echo $signUpError;
            }
        }

        if (1 > count($reg_errors->get_error_messages())) {
            $user = get_user_by('email', $user_login);

            wp_set_password($new_password, $user->ID);
            header('Location:' . home_url() . '/giris-yap');

            if (is_wp_error($reg_errors)) {
                foreach ($reg_errors->get_error_messages() as $error) {
                    $signUpError = '<p style="color:#FF0000; text-aling:left;"><strong>ERROR</strong>: ' . $error . '<br /></p>';
                    echo $signUpError;
                }
            }
        }
    }
}

?>

    <body>

    <!-- Content -->
    <div class="login-body resetpassword">
        <div class="content">
            <!-- Logo -->
            <a href=<?php echo home_url(); ?>>
                <?php $logo = get_field('logo', get_option('page_on_front')); ?>
                <img class="logo" src="<?php echo $logo['siyah_logo'] ?>" alt="Logo">
            </a>

            <div class="forms">
                <div class="tab-content">
                    <!-- Login -->
                    <div id="cont-tab1" role="tabpanel" class="tab-pane active">
                        <form action="" method="get">
                            <div class="title">
                                <a href="#"><img src="dist/img/icon/b-nav-left.svg" alt=""></a>
                                <div class="content">
                                    <h1>Yeni Şifre Belirle</h1>
                                    <p>Harika! Artık yeni şifre belirleyebilirsiniz.</p>
                                </div>
                            </div>
                            <div class="input-group">
                                <label for="">Yeni Şifre</label>
                                <input type="password" maxlength="16" minlength="8" name="new_password">
                                <input type="hidden" name="login" value="<?php echo $user_login ?>">
                                <input type="hidden" name="action" value="rp">
                                <input type="hidden" name="key" value="<?php echo $user_key ?>">
                            </div>
                            <div class="input-group">
                                <label for="">Tekrar Yeni Şifre</label>
                                <input type="password" maxlength="16" minlength="8" name="new_password_again">
                            </div>
                            <button class="ui-button--primary ui-button-size--large" name="reset_password">Şifreyi
                                Onayla
                            </button>
                        </form>
                    </div>
                </div>
            </div>
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
    <script src="dist/js/product-slider-min.js"></script>
    </body>

<?php wp_footer() ?>