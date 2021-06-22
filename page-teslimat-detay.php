<?php
/*
 * Template Name: My shipping Detail
 */
if (!is_user_logged_in()) {
    header('Location:' . home_url() . '/giris-yap');
}

if (isset($_POST['update_shipping'])) {
    $post_code = $_POST['postcode'];
    $error = false;
    if ($post_code[0] > 8 || ($post_code[0] == 8 && $post_code[1] > 1)) {
        alert("Geçersiz posta kodu girdiniz");
    }

    if (!$error) {
        update_user_meta(wp_get_current_user()->ID, "shipping_country", $_POST['country']);
        update_user_meta(wp_get_current_user()->ID, "shipping_city", $_POST['city']);
        update_user_meta(wp_get_current_user()->ID, "shipping_state", $_POST['state']);
        update_user_meta(wp_get_current_user()->ID, "shipping_address_1", $_POST['address_1']);
        update_user_meta(wp_get_current_user()->ID, "shipping_address_2", $_POST['address_2']);
        update_user_meta(wp_get_current_user()->ID, "shipping_postcode", $_POST['postcode']);
        update_user_meta(wp_get_current_user()->ID, "shipping_first_name", $_POST['first_name']);
        update_user_meta(wp_get_current_user()->ID, "shipping_last_name", $_POST['last_name']);
        update_user_meta(wp_get_current_user()->ID, "shipping_company", $_POST['company']);
        update_user_meta(wp_get_current_user()->ID, "shipping_phone", $_POST['phone']);
        update_user_meta(wp_get_current_user()->ID, "shipping_email", $_POST['email']);
        update_user_meta(wp_get_current_user()->ID, "shipping_saved", "1");


    }

}

$shipping_first_name = get_user_meta(wp_get_current_user()->ID, 'shipping_first_name', true);
$shipping_last_name = get_user_meta(wp_get_current_user()->ID, 'shipping_last_name', true);
$shipping_company = get_user_meta(wp_get_current_user()->ID, 'shipping_company', true);
$shipping_address_1 = get_user_meta(wp_get_current_user()->ID, 'shipping_address_1', true);
$shipping_address_2 = get_user_meta(wp_get_current_user()->ID, 'shipping_address_2', true);
$shipping_city = get_user_meta(wp_get_current_user()->ID, 'shipping_city', true);
$shipping_state = get_user_meta(wp_get_current_user()->ID, 'shipping_state', true);
$shipping_postcode = get_user_meta(wp_get_current_user()->ID, 'shipping_postcode', true);
$shipping_country = get_user_meta(wp_get_current_user()->ID, 'shipping_country', true);
$shipping_phone = get_user_meta(wp_get_current_user()->ID, 'shipping_phone', true);
$shipping_email = get_user_meta(wp_get_current_user()->ID, 'shipping_email', true);

get_header();
?>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
            <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
            <li><a href="/adreslerim">Adreslerim</a></li>
            <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
            <li><a class="disable" href="#">Teslimat Detay</a></li>
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
                            <li class="active"><a href="/adreslerim">Adreslerim</a></li>
                            <li><a href="/favorilerim">Favorilerim</a></li>
                            <li><a href="/promosyonlarim">İndirim Kuponlarım</a></li>
                            <li><a href="/hesabim">Kullanıcı Bilgilerim</a></li>
                            <li><a href="<?php echo wp_logout_url(home_url()) ?>">Çıkış Yap</a></li>
                        </ul>
                    </div>
                </div>
                <?php
                $meta = get_user_meta(wp_get_current_user()->ID);

                $first_name = $meta['first_name'][0];
                $last_name = $meta['last_name'][0];
                $phone = $meta['phone'][0];
                $email = wp_get_current_user()->data->user_email;
                ?>
                <div class="col-md-8">
                    <div class="account-info">
                        <form action="" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Adres Bilgileri</h5>
                                    <?php get_country_list($shipping_country, $shipping_state, $shipping_city); ?>
                                    <script>
                                        function countAddress_1(val) {
                                            var len = val.value.length;
                                            var maxLength = 50;
                                            $("#addres_1_counter").text(len + "/" + maxLength)
                                            if (len >= maxLength) {
                                                $("#address_2").focus()
                                            }
                                        }

                                        function countAddress_2(val) {
                                            var len = val.value.length;
                                            var maxLength = 50;
                                            $("#addres_2_counter").text(len + "/" + maxLength)
                                        }
                                    </script>
                                    <div class="input-group">
                                        <label for="">Adres - 1 <span>*</span></label>
                                        <input maxlength="50" required onkeyup="countAddress_1(this)" name="address_1"
                                               placeholder="Mahalle, sokak ve cadde bilgisi"
                                               value="<?php echo $shipping_address_1 ?>" type="text">
                                        <div id="addres_1_counter"><?php echo strlen($shipping_address_1) ?>/50</div>
                                    </div>
                                    <div class="input-group">
                                        <label for="">Adres - 2 <span>*</span></label>
                                        <input maxlength="50" required name="address_2" id="address_2"
                                               onkeyup="countAddress_2(this)"
                                               placeholder="Bina numarası, blok ve daire bilgisi"
                                               value="<?php echo $shipping_address_2 ?>" type="text">
                                        <div id="addres_2_counter"><?php echo strlen($shipping_address_2) ?>/50</div>
                                    </div>
                                    <div class="input-group">
                                        <label for="">Posta Kodu <span>*</span></label>
                                        <input required name="postcode" value="<?php echo $shipping_postcode ?>"
                                               type="text"
                                               pattern="\d*" minlength="5" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Kişisel Bilgiler</h5>
                                    <div class="input-group">
                                        <label for="">Adınız <span>*</span></label>
                                        <input maxlength="32" required name="first_name" type="text"
                                               value="<?php echo $shipping_first_name ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Soyadınız <span>*</span></label>
                                        <input maxlength="32" required name="last_name" type="text"
                                               value="<?php echo $shipping_last_name ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Şirket Bilgisi (Opsiyonel)</label>
                                        <input name="company" type="text" value="<?php echo $shipping_company ?>">
                                    </div>
                                    <button type="submit" class="ui-button--primary ui-button-size--large"
                                            name="update_shipping">
                                        Değişiklikleri Kaydet
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer() ?>