<?php
/*
 * Template Name: My Billing Detail
 */
if (!is_user_logged_in()) {
    header('Location:' . home_url() . '/giris-yap');
}

if (isset($_POST['update_billing'])) {
    $post_code = $_POST['postcode'];
    $error = false;
    if ($post_code[0] > 8 || ($post_code[0] == 8 && $post_code[1] > 1)) {
        alert("Geçersiz posta kodu girdiniz");
    }

    if (!$error) {

        update_user_meta(wp_get_current_user()->ID, "billing_country", $_POST['country']);
        update_user_meta(wp_get_current_user()->ID, "billing_city", $_POST['city']);
        update_user_meta(wp_get_current_user()->ID, "billing_state", $_POST['state']);
        update_user_meta(wp_get_current_user()->ID, "billing_address_1", $_POST['address_1']);
        update_user_meta(wp_get_current_user()->ID, "billing_address_2", $_POST['address_2']);
        update_user_meta(wp_get_current_user()->ID, "billing_postcode", $_POST['postcode']);
        update_user_meta(wp_get_current_user()->ID, "billing_first_name", $_POST['first_name']);
        update_user_meta(wp_get_current_user()->ID, "billing_last_name", $_POST['last_name']);
        update_user_meta(wp_get_current_user()->ID, "billing_company", $_POST['company']);
        update_user_meta(wp_get_current_user()->ID, "billing_phone", $_POST['phone']);
        update_user_meta(wp_get_current_user()->ID, "billing_email", $_POST['email']);
        update_user_meta(wp_get_current_user()->ID, "billing_tc", $_POST['tc']);
        update_user_meta(wp_get_current_user()->ID, "billing_tax_number", $_POST['tax_number']);
        update_user_meta(wp_get_current_user()->ID, "billing_saved", "1");
        alert("Bilgileriniz başarıyla güncellendi");

        if (isset($_POST['same_shipping'])) {
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

        } else
            update_user_meta(wp_get_current_user()->ID, "same_shipping", 0);
    }

}


$billing_first_name = get_user_meta(wp_get_current_user()->ID, 'billing_first_name', true);
$billing_last_name = get_user_meta(wp_get_current_user()->ID, 'billing_last_name', true);
$billing_company = get_user_meta(wp_get_current_user()->ID, 'billing_company', true);
$billing_address_1 = get_user_meta(wp_get_current_user()->ID, 'billing_address_1', true);
$billing_address_2 = get_user_meta(wp_get_current_user()->ID, 'billing_address_2', true);
$billing_city = get_user_meta(wp_get_current_user()->ID, 'billing_city', true);
$billing_state = get_user_meta(wp_get_current_user()->ID, 'billing_state', true);
$billing_postcode = get_user_meta(wp_get_current_user()->ID, 'billing_postcode', true);
$billing_country = get_user_meta(wp_get_current_user()->ID, 'billing_country', true);
$billing_phone = get_user_meta(wp_get_current_user()->ID, 'billing_phone', true);
$billing_email = get_user_meta(wp_get_current_user()->ID, 'billing_email', true);
$billing_tc = get_user_meta(wp_get_current_user()->ID, 'billing_tc', true);
$billing_tax_number = get_user_meta(wp_get_current_user()->ID, 'billing_tax_number', true);
$same_shipping = get_user_meta(wp_get_current_user()->ID, 'same_billing', true);

get_header();
?>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
            <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
            <li><a href="/adreslerim">Adreslerim</a></li>
            <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
            <li><a class="disable" href="#">Fatura Detay</a></li>
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
                                    <h5>Fatura Adresi Bilgileri</h5>
                                    <?php get_country_list($billing_country, $billing_state,$billing_city); ?>
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
                                               value="<?php echo $billing_address_1 ?>" type="text">
                                        <div id="addres_1_counter"><?php echo strlen($billing_address_1) ?>/50</div>
                                    </div>
                                    <div class="input-group">
                                        <label for="">Adres - 2 <span>*</span></label>
                                        <input maxlength="50" required name="address_2" id="address_2"
                                               onkeyup="countAddress_2(this)"
                                               placeholder="Bina numarası, blok ve daire bilgisi"
                                               value="<?php echo $billing_address_2 ?>" type="text">
                                        <div id="addres_2_counter"><?php echo strlen($billing_address_2) ?>/50</div>
                                    </div>
                                    <div class="input-group">
                                        <label for="">Posta Kodu <span>*</span></label>
                                        <input name="postcode" value="<?php echo $billing_postcode ?>" type="text" pattern="\d*" minlength="5" maxlength="5">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>Fatura Bilgileri</h5>
                                    <div class="input-group">
                                        <label for="">Adınız <span>*</span></label>
                                        <input maxlength="32" required name="first_name" type="text" value="<?php echo $billing_first_name ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Soyadınız <span>*</span></label>
                                        <input maxlength="32" required name="last_name" type="text" value="<?php echo $billing_last_name ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Şirket Bilgisi (Opsiyonel)</label>
                                        <input name="company" type="text" value="<?php echo $billing_company ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Cep Telefon Numaranız <span>*</span></label>
                                        <input id="phone" required name="phone" type="text" value="<?php echo $billing_phone ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">E-posta Adresiniz <span>*</span></label>
                                        <input required name="email" type="email" value="<?php echo $billing_email ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">T.C Kimlik No (Opsiyonel)<span></span></label>
                                        <input name="tc" type="text" value="<?php echo $billing_tc ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="">Vergi No (Opsiyonel)<span></span></label>
                                        <input name="tax_number" type="text" value="<?php echo $billing_tax_number ?>">
                                    </div>
                                    <div class="input-group">
                                        <label for="same_shipping">Teslimat adresim, fatura adresim ile aynı</label>
                                        <?php if ($same_shipping): ?>
                                            <input id="same_shipping" name="same_shipping" type="checkbox" checked>
                                        <?php else: ?>
                                            <input id="same_shipping" name="same_shipping" type="checkbox">
                                        <?php endif ?>
                                    </div>
                                    <button type="submit" class="ui-button--primary ui-button-size--large"
                                            name="update_billing">
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