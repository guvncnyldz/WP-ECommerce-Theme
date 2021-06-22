<?php
/*
 * Template Name: My Account
 */
if (!is_user_logged_in()) {
    header('Location:' . home_url() . '/giris-yap');
} else
    if (isset($_POST['update_user'])) {
        $old_email = wp_get_current_user()->data->user_email;
        global $reg_errors;
        $reg_errors = new WP_Error;
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $error = false;
        if (!error && empty($first_name) || empty($last_name) || empty($email)) {
            alert("Lütfen zorunlu alanları doldurun");
            $error = true;
        }

        if (!$error && !is_email($email)) {
            alert("Lütfen geçerli bir e-posta adresi girin");
            $error = true;
        }

        if (!$error && $email != $old_email && email_exists($email)) {
            alert("E-posta adresi kullanılmakta");
            $error = true;
        }

        if (!$error) {
            // sanitize user form input
            global $username, $useremail;
            $useremail = sanitize_email($_POST['useremail']);
            $password = esc_attr($_POST['password']);

            $userdata = array(
                'ID' => wp_get_current_user()->ID,
                'user_login' => $first_name . " " . $last_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'user_email' => $email
            );

            $user = wp_update_user($userdata);
            alert("Hesabınız başarıyla güncellendi");

            if (isset($_POST['newsletter'])) {
                update_user_meta(wp_get_current_user()->ID, "newsletter", 'true');
            } else {
                update_user_meta(wp_get_current_user()->ID, "newsletter", 'false');
            }

            update_user_meta(wp_get_current_user()->ID, "phone", $phone);
        }
    } else if (isset($_POST['update_password'])) {
        global $reg_errors;
        $reg_errors = new WP_Error;
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $new_password_again = $_POST['new_password_again'];
        $error = false;
        if (!$error && empty($old_password) || empty($new_password) || empty($new_password_again)) {
            alert("Lütfen zorunlu alanları doldurun");
            $error = true;
        }

        if (!$error && $new_password != $new_password_again) {
            alert("Şifreler uyuşmuyor");
            $error = true;
        }

        if (!$error) {
            $user = get_user_by('email', wp_get_current_user()->data->user_email);

            if (!wp_check_password($old_password, $user->user_pass, $user->ID)) { //bad password
                alert("Girmiş olduğunuz şifre hatalı");
            } else if($old_password == $new_password)
            {
                alert("Lütfen eskisinden farklı bir şifre girin");
            }
            else {
                wp_set_password($new_password, $user->ID);
                alert("Şifreniz başarıyla güncellendi");
            }

        }
    }

get_header();
?>

    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
            <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
            <li><a class="disable" href="#">Hesabım</a></li>
        </ul>
    </div>

    <div class="container">
        <div class="account">
            <div class="row">
                <div class="col-md-4">
                    <div class="account-menu">
                        <div class="menu-title">
                            <span>HESABIM</span>
                            <?php
                            $meta = get_user_meta(wp_get_current_user()->ID);
                            $first_name = $meta['first_name'][0];
                            $last_name = $meta['last_name'][0];
                            ?>
                            <h5><?php echo $first_name . " " . $last_name ?></h5>
                        </div>
                        <ul>
                            <li><a href="/siparislerim">Siparişlerim</a></li>
                            <li><a href="/adreslerim">Adreslerim</a></li>
                            <li><a href="/favorilerim">Favorilerim</a></li>
                            <li><a href="/promosyonlarim">İndirim Kuponlarım</a></li>
                            <li class="active"><a href="/hesabim">Kullanıcı Bilgilerim</a></li>
                            <li><a href="<?php echo wp_logout_url(home_url()) ?>">Çıkış Yap</a></li>
                        </ul>
                    </div>
                </div>
                <?php
                $meta = get_user_meta(wp_get_current_user()->ID);

                $first_name = $meta['first_name'][0];
                $last_name = $meta['last_name'][0];
                $notification = $meta['newsletter'][0];
                $phone = $meta['phone'][0];
                $email = wp_get_current_user()->data->user_email;
                ?>
                <div class="col-md-8">
                    <div class="account-info">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Kullanıcı Bilgilerim</h5>
                                <form action="" method="post">
                                    <div class="input-group">
                                        <label for="">Adınız</label>
                                        <input maxlength="32" type="text" name="first_name" value="<?php echo $first_name ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Soyadınız</label>
                                        <input maxlength="32" type="text" name="last_name" value="<?php echo $last_name ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Cep Telefon Numaranız</label>
                                        <input id="phone" type="text" name="phone" value="<?php echo $phone ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">E-posta Adresiniz</label>
                                        <input type="email" name="email" value="<?php echo $email ?>">
                                    </div>
<!--                                    <label class="control control--checkbox">
                                        <span>Kampanya ve yeni gelen ürünlerden haberdar olmak istiyorum.</span>
                                        <?php
/*                                        if ($notification == 'true') {
                                            echo '<input type="checkbox" name="newsletter" checked/>';
                                        } else {
                                            echo '<input type="checkbox" name="newsletter"/>';
                                        }
                                        */?>
                                        <div class="control__indicator"></div>
                                    </label>-->
                                    <button class="ui-button--primary ui-button-size--large" name="update_user">
                                        Değişiklikleri Kaydet
                                    </button>
                                </form>
                            </div>
                            <?php if(get_user_meta(wp_get_current_user()->ID,'social-login',true) != '1'): ?>
                            <div class="col-md-6">
                                <h5>Şifremi Değiştirmek İstiyorum</h5>
                                <form action="" method="post">
                                    <div class="input-group">
                                        <label for="">Eski Şifreniz</label>
                                        <input maxlength="16" minlength="0" type="password" name="old_password" value="">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Yeni Şifreniz</label>
                                        <input maxlength="16" minlength="0" type="password" name="new_password" value="">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Tekrar Yeni Şifreniz</label>
                                        <input maxlength="16" minlength="0" type="password" name="new_password_again" value="">
                                    </div>
                                    <button class="ui-button--secondary ui-button-size--large" name="update_password">
                                        Şifremi Değiştir
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer() ?>
