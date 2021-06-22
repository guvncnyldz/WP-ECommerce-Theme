<?php
/*
 * Template Name: My Address
 */
if (!is_user_logged_in()) {
    header('Location:' . home_url() . '/giris-yap');
}

if (isset($_POST['clear_shipping'])) {
    update_user_meta(wp_get_current_user()->ID, "shipping_country", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_city", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_state", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_address_1", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_address_2", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_postcode", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_first_name", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_last_name", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_company", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_phone", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_email", "");
    update_user_meta(wp_get_current_user()->ID, "shipping_saved", "0");
} else if (isset($_POST['clear_billing'])) {
    update_user_meta(wp_get_current_user()->ID, "billing_country", "");
    update_user_meta(wp_get_current_user()->ID, "billing_city", "");
    update_user_meta(wp_get_current_user()->ID, "billing_state", "");
    update_user_meta(wp_get_current_user()->ID, "billing_address_1", "");
    update_user_meta(wp_get_current_user()->ID, "billing_address_2", "");
    update_user_meta(wp_get_current_user()->ID, "billing_postcode", "");
    update_user_meta(wp_get_current_user()->ID, "billing_first_name", "");
    update_user_meta(wp_get_current_user()->ID, "billing_last_name", "");
    update_user_meta(wp_get_current_user()->ID, "billing_company", "");
    update_user_meta(wp_get_current_user()->ID, "billing_phone", "");
    update_user_meta(wp_get_current_user()->ID, "billing_email", "");
    update_user_meta(wp_get_current_user()->ID, "billing_tc", "");
    update_user_meta(wp_get_current_user()->ID, "billing_tax_number", "");
    update_user_meta(wp_get_current_user()->ID, "billing_saved", "0");
}

get_header();

$billing = WC()->customer->get_billing();
$billing_first_name = WC()->customer->get_billing_first_name();
$billing_last_name = WC()->customer->get_billing_last_name();
$billing_company = WC()->customer->get_billing_company();
$billing_address = WC()->customer->get_billing_address();
$billing_address_1 = WC()->customer->get_billing_address_1();
$billing_address_2 = WC()->customer->get_billing_address_2();
$billing_city = WC()->customer->get_billing_city();
$billing_state = WC()->customer->get_billing_state();
$billing_postcode = WC()->customer->get_billing_postcode();
$billing_country = WC()->customer->get_billing_country();
$billing_saved = get_user_meta(wp_get_current_user()->ID, 'billing_saved', true);

//shipping details
$shipping = WC()->customer->get_shipping();
$shipping_first_name = WC()->customer->get_shipping_first_name();
$shipping_last_name = WC()->customer->get_shipping_last_name();
$shipping_company = WC()->customer->get_shipping_company();
$shipping_address = WC()->customer->get_shipping_address();
$shipping_address_1 = WC()->customer->get_shipping_address_1();
$shipping_address_2 = WC()->customer->get_shipping_address_2();
$shipping_city = WC()->customer->get_shipping_city();
$shipping_state = WC()->customer->get_shipping_state();
$shipping_postcode = WC()->customer->get_shipping_postcode();
$shipping_country = WC()->customer->get_shipping_country();
$shipping_saved = get_user_meta(wp_get_current_user()->ID, 'shipping_saved', true);

?>
    <style>
        a:hover {
            color: #D09A68;
        }
    </style>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
            <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
            <li><a class="disable" href="#">Adreslerim</a></li>
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
                <div class="col-md-8">
                    <div class="account-info">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Teslimat Adresi</h5>
                                <?php if ($shipping_saved): ?>
                                    <div class="adress-box">
                                        <?php
                                        $states = WC()->countries->get_states($shipping_country);
                                        $shipping_state = !empty($states[$shipping_state]) ? $states[$shipping_state] : $shipping_state;
                                        $shipping_country = WC()->countries->countries[$shipping_country];
                                        ?>
                                        <h6><?php echo $shipping_first_name . " " . $shipping_last_name ?></h6>
                                        <p><?php echo $shipping_address_1 . " " . $shipping_address_2 . " " . $shipping_postcode . " " . $shipping_state . " " . $shipping_country ?></p>
                                    </div>
                                <?php else: ?>
                                    <a href="/teslimat-detay">
                                        <div style="color: #D09A68" class="adress-box">
                                            Adres Ekle
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <form action="" method="post">
                                    <button type="submit" name="clear_shipping">Temizle</button>
                                </form>
                                <a class="adress-edit noHover" href="/teslimat-detay">
                                    Bilgileri Güncelle
                                    <img src="<?php bloginfo('template_url') ?>/img/icon/edit.svg" alt="">
                                </a>
                            </div>
                            <div class="col-md-6">
                                <h5>Fatura Adresi</h5>
                                <?php if ($billing_saved): ?>

                                <div class="adress-box">
                                    <?php
                                    $states = WC()->countries->get_states($billing_country);
                                    $billing_state = !empty($states[$billing_state]) ? $states[$billing_state] : $billing_state;
                                    $shipping_country = WC()->countries->countries[$shipping_country];
                                    ?>
                                    <h6><?php echo $billing_first_name . " " . $billing_last_name ?></h6>
                                    <p><?php echo $billing_address_1 . " " . $billing_address_2 . " " . $billing_postcode . " " . $billing_state . " " . $billing_country ?></p>
                                </div>
                                <?php else: ?>
                                    <a href="/fatura-detay">
                                        <div style="color: #D09A68" class="adress-box">
                                            Adres Ekle
                                        </div>
                                    </a>
                                <?php endif; ?>
                                <form action="" method="post">
                                    <button type="submit" name="clear_billing">Temizle</button>
                                </form>
                                <a class="adress-edit noHover" href="/fatura-detay">
                                    Bilgileri Güncelle
                                    <img src="<?php bloginfo('template_url') ?>/img/icon/edit.svg" alt="">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer() ?>