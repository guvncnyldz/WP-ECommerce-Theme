<?php
if (is_user_logged_in()) {
    header('Location:' . home_url());
}

$mail_sended = false;
if (isset($_POST['forgot_password'])) {
    $user_login = $_POST['user_login'];

    if (my_retrieve_password($user_login) == true) {
        $mail_sended = true;
    }
}

function my_retrieve_password( $user_login = null ) {
    $errors    = new WP_Error();
    $user_data = false;

    // Use the passed $user_login if available, otherwise use $_POST['user_login'].
    if ( ! $user_login && ! empty( $_POST['user_login'] ) ) {
        $user_login = $_POST['user_login'];
    }

    if ( empty( $user_login ) ) {
        $errors->add( 'empty_username', __( '<strong>Error</strong>: Please enter a username or email address.' ) );
    } elseif ( strpos( $user_login, '@' ) ) {
        $user_data = get_user_by( 'email', trim( wp_unslash( $user_login ) ) );
        if ( empty( $user_data ) ) {
            $errors->add( 'invalid_email', __( '<strong>Error</strong>: There is no account with that username or email address.' ) );
        }
    } else {
        $user_data = get_user_by( 'login', trim( wp_unslash( $user_login ) ) );
    }


    $user_data = apply_filters( 'lostpassword_user_data', $user_data, $errors );


    do_action( 'lostpassword_post', $errors, $user_data );


    $errors = apply_filters( 'lostpassword_errors', $errors, $user_data );

    if ( $errors->has_errors() ) {
        return $errors;
    }

    if ( ! $user_data ) {
        $errors->add( 'invalidcombo', __( '<strong>Error</strong>: There is no account with that username or email address.' ) );
        return $errors;
    }

    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key        = get_password_reset_key( $user_data );

    if ( is_wp_error( $key ) ) {
        return $key;
    }

    $locale = get_user_locale( $user_data );

    $switched_locale = switch_to_locale( $locale );

    if ( is_multisite() ) {
        $site_name = get_network()->site_name;
    } else {

        $site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    }

    $message = __( 'Someone has requested a password reset for the following account:' ) . "\r\n\r\n";
    /* translators: %s: Site name. */
    $message .= sprintf( __( 'Site Name: %s' ), $site_name ) . "\r\n\r\n";
    /* translators: %s: User login. */
    $message .= sprintf( __( 'Username: %s' ), $user_login ) . "\r\n\r\n";
    $message .= __( 'If this was a mistake, ignore this email and nothing will happen.' ) . "\r\n\r\n";
    $message .= __( 'To reset your password, visit the following address:' ) . "\r\n\r\n";
    $message .= network_site_url( "sifre-sifirla?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";

    if ( ! is_user_logged_in() ) {
        $requester_ip = $_SERVER['REMOTE_ADDR'];
        if ( $requester_ip ) {
            $message .= sprintf(
                /* translators: %s: IP address of password reset requester. */
                    __( 'This password reset request originated from the IP address %s.' ),
                    $requester_ip
                ) . "\r\n";
        }
    }

    $title = sprintf( __( '[%s] Password Reset' ), $site_name );


    $title = apply_filters( 'retrieve_password_title', $title, $user_login, $user_data );


    $message = apply_filters( 'retrieve_password_message', $message, $key, $user_login, $user_data );

    if ( $switched_locale ) {
        restore_previous_locale();
    }

    if ( $message && ! wp_mail( $user_email, wp_specialchars_decode( $title ), $message ) ) {
        $errors->add(
            'retrieve_password_email_failure',
            sprintf(
            /* translators: %s: Documentation URL. */
                __( '<strong>Error</strong>: The email could not be sent. Your site may not be correctly configured to send emails. <a href="%s">Get support for resetting your password</a>.' ),
                esc_url( __( 'https://wordpress.org/support/article/resetting-your-password/' ) )
            )
        );
        return $errors;
    }

    return true;
}

?>
<?php wp_head() ?>

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
                    <?php if ($mail_sended): ?>
                        <form action="" method="post">
                            <div class="title">
                                <a href="/giris-yap"><img src="dist/img/icon/b-nav-left.svg" alt=""></a>
                                <div class="content">
                                    <h1>E-Posta Gönderildi</h1>
                                    <p>Girmiş olduğunuz adrese şifre sıfırlama bağlantısı gönderildi</p>
                                </div>
                            </div>
                        </form>
                    <?php else: ?>
                        <form action="" method="post">
                            <div class="title">
                                <a href="/giris-yap"><img src="dist/img/icon/b-nav-left.svg" alt=""></a>
                                <div class="content">
                                    <h1>Şifremi Unuttum</h1>
                                    <p>Şifre yenileme bağlantısını gönderebilmemiz için e-posta adresinize ihtiyacımız
                                        var.</p>
                                </div>
                            </div>
                            <div class="input-group">
                                <label for="">E-posta Adresiniz</label>
                                <input type="email" name="user_login">
                            </div>
                            <button class="ui-button--primary ui-button-size--large" name="forgot_password">Kurtarma
                                Linki
                                Gönder
                            </button>
                        </form>
                    <?php endif; ?>
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

