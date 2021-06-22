<?php
$max_number = get_field('maks_sepet', get_option('page_on_front'));
$cart_items_count = WC()->cart->get_cart_contents_count();
console_log($_POST);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $woocommerce;
    $change_quantity = false;
    if (array_key_exists('add_to_cart', $_POST)) {
        add_to_cart($_POST, $cart_items_count, $max_number);
    } else if (array_key_exists('add_to_cart_sample', $_POST)) {
        add_to_cart_sample($_POST, $cart_items_count, $max_number);
    } else if (array_key_exists('apply_coupon', $_POST)) {
        apply_coupon($_POST);
    } else if (array_key_exists('clear_cart', $_POST)) {
        WC()->cart->empty_cart();
    } else if (array_key_exists('change_quantity', $_POST)) {
        global $woocommerce;

        foreach ($woocommerce->cart->cart_contents as $cart_item) {
            if ($cart_item['key'] == $_POST['item_key']) {
                if ($cart_item['quantity'] != $_POST['change_quantity']) {
                    $change_quantity = true;
                    if ($_POST['change_quantity'] == 0) {
                        WC()->cart->remove_cart_item($_POST['item_key']);
                        break;
                    } else {
                        if ($cart_items_count - $cart_item['quantity'] + $_POST['change_quantity'] <= $max_number)
                            $woocommerce->cart->set_quantity($_POST['item_key'], $_POST['change_quantity']);
                        break;
                    }
                } else {
                    break;
                }
            }
        }
    }

    if (!$change_quantity && array_key_exists('minus_quantity', $_POST)) {
        global $woocommerce;
        if ($_POST['minus_quantity'] == 0) {
            WC()->cart->remove_cart_item($_POST['item_key']);
        } else {
            $woocommerce->cart->set_quantity($_POST['item_key'], $_POST['minus_quantity']);
        }
    } else if (!$change_quantity && array_key_exists('plus_quantity', $_POST)) {
        global $woocommerce;
        if ($_POST['plus_quantity'] == 0) {
            WC()->cart->remove_cart_item($_POST['item_key']);
        } else {
            foreach ($woocommerce->cart->cart_contents as $cart_item) {
                if ($cart_item['key'] == $_POST['item_key']) {
                    if ($cart_items_count - $cart_item['quantity'] + $_POST['plus_quantity'] <= $max_number)
                        $woocommerce->cart->set_quantity($_POST['item_key'], $_POST['plus_quantity']);
                    break;
                }
            }
        }
    }
}

function add_to_cart($data, $cart_items_count, $max_number)
{
    $product_id = $data['product_id'];
    if (isset($data['color'])) {
        $extra_style_count = get_field('renk_sayisi', $product_id);
        $extra_style_text = get_field('renk_yazisi', $product_id);
        $color_index = $data['color'];
        $product = wc_get_product($product_id);
        $attachment_ids = $product->get_gallery_image_ids();

        $color = $extra_style_text . "" . ($color_index + 1);

        if ($color_index == 0) {
            $color_url = wp_get_attachment_url($attachment_ids[0]);
            $sku = $product->get_sku();
        } else {
            $sku = $product->get_sku() . "-" . ($color_index + 1);
            $reversed_array = array_reverse($attachment_ids);
            console_log($reversed_array);
            $color_url = wp_get_attachment_url($reversed_array[$extra_style_count - $color_index]);
        }
    }

    $width = $data['width'];
    $height = $data['height'];
    $width_cm = $data['width_cm'];
    $height_cm = $data['height_cm'];
    $pos_x = $data['pos_x'];
    $pos_y = $data['pos_y'];
    $material_name = $data['material_name'];
    $current_price = $data['price'];

    $quantity = 1;

    if (isset($data['quantity']))
        $quantity = $data['quantity'];

    if ($cart_items_count + $quantity > $max_number)
        return;

    $custom_data = array(
        'Kesme Eni Px' => $width,
        'Kesme Boyu Px' => $height,
        'En cm' => $width_cm,
        'Boy cm' => $height_cm,
        'Pozisyon X' => $pos_x,
        'Pozisyon Y' => $pos_y,
        'Renk Seçimi' => $color,
        'Resim URL' => $color_url,
        'Malzeme Adı' => $material_name,
        'Renk SKU' => $sku,
        'Fiyat' => $current_price,
    );

    global $woocommerce;
    $result = $woocommerce->cart->add_to_cart($product_id, $quantity, null, null, $custom_data);
}

function add_to_cart_sample($data, $cart_items_count, $max_number)
{
    if ($cart_items_count + 1 > $max_number)
        return;

    $product_id = $data['product_id'];
    $sample_price = get_field('ornek_urun', get_option('page_on_front'));

    $custom_data = array(
        'Fiyat' => $sample_price,
        'Tip' => "Örnek Ürün"
    );

    global $woocommerce;
    $woocommerce->cart->add_to_cart($product_id, 1, null, null, $custom_data);
}

function apply_coupon($data)
{
    $coupon_code = $data['coupon'];

    if (WC()->cart->has_discount($coupon_code)) return;

    WC()->cart->apply_coupon($coupon_code);

}

WC()->cart->calculate_totals();
?>

<?php get_header() ?>

<div class="container">
    <ul class="breadcrumb">
        <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
        <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
        <li><a class="disable" href="#">Sepetim</a></li>
    </ul>
</div>

<div class="container">
    <div class="my-orders my-basket">
        <h4>Sepetim</h4>
        <h10>Maksimum: <?php echo $max_number ?></h10>
        <table class="footable">
            <thead>
            <td>Ürün Bilgisi</td>
            <td data-breakpoints="phone">Özelleştirmeler</td>
            <td data-breakpoints="phone">Adet</td>
            <td data-breakpoints="phone" class="price-title">Toplam Fiyat</td>
            <td data-breakpoints="phone" class="delete-item"></td>
            </thead>
            <tbody>
            <?php
            global $woocommerce;
            $items = $woocommerce->cart->get_cart();

            foreach ($items as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                $product_id = $product->get_id();
                echo '<tr>
                <td class="product-title">
                    <a href="' . get_permalink($product_id) . '">';
                if (isset($cart_item['Kesme Eni Px'])) {
                    $targ_w = $cart_item['Kesme Eni Px'];
                    $targ_h = $cart_item['Kesme Boyu Px'];
                    $targ_pos_x = $cart_item['Pozisyon X'];
                    $targ_pos_y = $cart_item['Pozisyon Y'];
                    $image = imagecreatefromjpeg(get_the_post_thumbnail_url($product_id));
                    $cropped_image = my_crop($image, $targ_pos_x, $targ_pos_y, $targ_w, $targ_h);
                    $data = ob_get_image($cropped_image);
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($data) . '" alt="">';
                } else
                    echo '<img src="' . get_the_post_thumbnail_url($product_id) . '" alt="">';
                echo '<h5>' . $product->get_name() . '</h5>';
                if (isset($cart_item['Renk SKU']))
                    echo '<span>' . $cart_item['Renk SKU'] . '</span>';
                else
                    echo '<span>' . $product->get_sku() . '</span>';
                echo '</a></td>';

                echo '<td>';
                if (isset($cart_item['En cm'])) {
                    echo '<span>' . $cart_item['En cm'] . ' x ' . $cart_item['Boy cm'] . ' cm<br>' . $cart_item['Malzeme Adı'] . '<br></span>';
                } else if (isset($cart_item['Tip']))
                    echo '<span>' . $cart_item['Tip'] . '</span>';
                if (isset($cart_item['Renk Seçimi']))
                    echo '<span>' . $cart_item['Renk Seçimi'] . '</span>';
                echo '</td>';

                echo '<td class="counter-box">
                <div class="qty">
                <form action="/sepet" method="post" name="">
                    <input type="hidden" name="item_key" value="' . $cart_item['key'] . '"/>
                    <button type="submit" style="display: inline-flex; align-items: flex-end; margin-top: 13px !important;" name="minus_quantity" value="' . ($cart_item['quantity'] - 1) . '" class="minus bg-dark">-</button>
                    <input type="number" class="count" name="change_quantity" value="' . $cart_item['quantity'] . '">
                    <button type="submit" style="display: inline-flex; align-items: flex-end; margin-top: 10px !important;" name="plus_quantity" value="' . ($cart_item['quantity'] + 1) . '" class="plus bg-dark">+</button>
                </form>
                </div>
            </td>
            <td>';
                echo '<div class="price">';
                echo ($cart_item['data']->get_price() * $cart_item['quantity']) . get_woocommerce_currency_symbol();
                echo '</div>';
                echo '</td>
            <td class="delete-item">
                <a href="#">
                    <img src="<?php bloginfo(template_url) ?>/img/icon/b-delete.svg" alt="">
                </a>
            </td>
            </tr>';
            }
            ?>
            </tbody>
        </table>
        <div class="total-section">
            <div class="total">
                <h6>Toplam Ödenecek Tutar</h6>
            </div>
            <div class="detail">
                        <span>
                            Kargo: <?php echo WC()->cart->get_shipping_total() ?><?php echo get_woocommerce_currency_symbol()?><br>
                            <?php
                            $coupon = WC()->cart->get_coupons();
                            if ($coupon) {
                                echo 'Promosyon: ';
                                $coupon_codes = array_keys($coupon);

                                foreach ($coupon_codes as $coupon_code) {
                                    $c = new WC_Coupon($coupon_code);
                                    echo $c->code . " ";
                                }
                            }

                            ?>
                        </span>
            </div>
            <div class="price">
                <?php
                $discount = WC()->cart->get_discount_total();

                if ($discount == 0) {
                    echo '<span>' . WC()->cart->total . get_woocommerce_currency_symbol().'</span>';
                } else {
                    echo '<b>' . (WC()->cart->total + $discount) . get_woocommerce_currency_symbol().'</b><br>';
                    echo '<span>' . WC()->cart->total . get_woocommerce_currency_symbol().'</span>';
                }
                ?>
            </div>
        </div>
        <div class="my-order-action">
            <form method="post" action="<?php echo wc_get_cart_url() ?>">
                <button type="submit" class="ui-button--secondary-outline ui-button-size--large" name="clear_cart">
                    Sepeti Temizle
                </button>
                <input type="text" name="coupon">
                <button type="submit" name="apply_coupon" class="ui-button--secondary ui-button-size--large">Promosyon
                    Uygula
                </button>
            </form>
            <style>
                a:hover {
                    color: white;
                }
            </style>
            <a href="<?php global $woocommerce;
            echo $woocommerce->cart->get_checkout_url(); ?>" class="ui-button--primary ui-button-size--large">Ödeme
                Adımına Geç</a>
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
