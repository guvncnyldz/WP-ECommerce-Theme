<?php
/*
 * Template Name: My Orders
 */
get_header();
if (!is_user_logged_in()) {
    header('Location:' . home_url() . '/giris-yap');
}
?>
<div class="container">
    <ul class="breadcrumb">
        <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
        <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
        <li><a class="disable" href="#">Siparişlerim</a></li>
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
                        <li class="active"><a href="/siparislerim">Siparişlerim</a></li>
                        <li><a href="/adreslerim">Adreslerim</a></li>
                        <li><a href="/favorilerim">Favorilerim</a></li>
                        <li><a href="/promosyonlarim">İndirim Kuponlarım</a></li>
                        <li><a href="/hesabim">Kullanıcı Bilgilerim</a></li>
                        <li><a href="<?php echo wp_logout_url(home_url()) ?>">Çıkış Yap</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="my-orders">
                    <h4>Siparişlerim</h4>
                    <?php
                    $customer_orders = get_posts(array(
                        'numberposts' => -1,
                        'meta_key' => '_customer_user',
                        'meta_value' => get_current_user_id(),
                        'post_type' => wc_get_order_types(),
                        'post_status' => array_keys(wc_get_order_statuses()),
                    ));

                    if ($customer_orders) {
                        echo '<table class="footable">';
                        echo '<tbody>';

                        foreach ($customer_orders as $customer_order) {
                            $order = wc_get_order($customer_order->ID);

                            $total_product_count = 0;


                            echo '<tr>';
                            echo '<td data-breakpoints="all" class="product">';
                            echo '<h5>Şipariş No: <b>' . $order->get_id() . '</b></h5>';
                            echo '<span>' . wp_date('j F Y', strtotime($order->get_date_created())) . '</span>';
                            foreach ($order->get_items() as $item_id => $item) {
                                $product = $item->get_product();
                                $image_id = $product->get_image_id();
                                $image_url = wp_get_attachment_image_url($image_id, 'full');
                                echo '<img src="' . $image_url . '" alt="">';
                                echo '<h5>Ürün: <b>' . $product->get_name() . ' ' . $product->get_sku() . '</b></h5>';
                                echo '<h5>Miktar: <b>' . $item->get_quantity() . '</b></h5>';
                                $total_product_count += $item->get_quantity();

                            }
                            echo '</td>';
                            echo '<td>';
                            echo '<span>' . $total_product_count . ' ürün satın aldınız</span>';
                            echo '</td>';
                            echo '<td>';
                            echo '<span><b>' . translate_order_status($order->get_status()) . '</b></span>';
                            echo '</td>';
                            echo '<td>';
                            echo '<div class="price">' . $order->get_total() . get_woocommerce_currency_symbol().'</div>';
                            echo '</td>';
                            echo '</tr>';

                        }
                        echo '</table>';
                    }
                    echo '</tbody>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
<script src="<?php bloginfo('template_url') ?>/js/footable.min.js"></script>
<script>
    $('.footable').footable({
        breakpoints: {
            phone: 600,
            tablet: 1200
        }
    });
</script>