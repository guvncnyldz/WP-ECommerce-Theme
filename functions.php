<?php
header('Content-Type: text/html; charset=utf-8');

$directory_uri = get_template_directory();

require_once $directory_uri . "/sociallogin/config.php";
require_once $directory_uri . "/my_classes/Category.php";
require_once $directory_uri . "/my_classes/Comment.php";
require_once $directory_uri . "/my_classes/Posts.php";
require_once $directory_uri . "/my_classes/Page.php";
require_once $directory_uri . "/my_classes/Coupon.php";
require_once $directory_uri . "/my_classes/Material.php";
require_once $directory_uri . "/my_classes/ImageOperation.php";
require_once $directory_uri . "/utils/util_log.php";
require_once $directory_uri . "/utils/util_str.php";
require_once $directory_uri . "/utils/util_translate.php";
require_once $directory_uri . "/utils/util_cc_dropdown.php";

add_action('wp_ajax_get_cities', 'get_cities');
function get_cities()
{
    $countries_obj = new WC_Countries();
    $states = $countries_obj->get_states($_POST['country_code']);
    echo json_encode($states, JSON_UNESCAPED_UNICODE);
    die();
}

add_filter('woocommerce_add_to_cart_validation', 'only_six_items_allowed_add_to_cart', 10, 3);

function only_six_items_allowed_add_to_cart($passed, $product_id, $quantity)
{

    $cart_items_count = WC()->cart->get_cart_contents_count();
    $total_count = $cart_items_count + $quantity;
    $max_number = get_field('maks_sepet', get_option('page_on_front'));

    if ($cart_items_count >= $max_number || $total_count > $max_number) {
        // Set to false
        $passed = false;
        // Display a message
        wc_add_notice(__("You can’t have more than 6 items in cart", "woocommerce"), "error");
    }
    return $passed;
}

add_filter('woocommerce_update_cart_validation', 'only_six_items_allowed_cart_update', 10, 4);
function only_six_items_allowed_cart_update($passed, $cart_item_key, $values, $updated_quantity)
{

    $cart_items_count = WC()->cart->get_cart_contents_count();
    $original_quantity = $values['quantity'];
    $total_count = $cart_items_count - $original_quantity + $updated_quantity;
    $max_number = get_field('maks_sepet', get_option('page_on_front'));

    if ($cart_items_count > $max_number || $total_count > $max_number) {
        // Set to false
        $passed = false;
        // Display a message
        wc_add_notice(__("You can’t have more than 6 items in cart", "woocommerce"), "error");
    }
    return $passed;
}

add_filter('default_checkout_billing_country', 'change_default_checkout_country');
function change_default_checkout_country()
{
    return 'TR'; // country code
}

add_action('woocommerce_before_calculate_totals', 'add_custom_price', 1000, 1);
function add_custom_price($cart)
{
    if (is_admin() && !defined('DOING_AJAX'))
        return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['Fiyat'])) {
            $cart_item['data']->set_price($cart_item['Fiyat']);
        }
    }
}

add_action('woocommerce_admin_order_item_headers', 'download_image_admin_order_item_headers', 10, 0);
function download_image_admin_order_item_headers()
{
    echo '<th class="item sortable" colspan="1" data-sort="string-ins">' . __('Alınan ürün', 'woocommerce') . '</th>';
}

add_action('woocommerce_admin_order_item_values', 'download_image_order_item_values', 10, 3);
function download_image_order_item_values($_product, $item, $item_id)
{
    $product_id = $item['product_id'];

    $product = new WC_Product($product_id);

    if (isset($item["Resim URL"])) {
        echo apply_filters(
            'woocommerce_single_product_image_html', sprintf(
            '<td class="name" colspan="1" ><img style="width: 200px; height: 200px" src="'.$item["Resim URL"].'" alt=""></td>'));
    } else {
        echo '<td></td>';
        echo '<td></td>';
    }
}

add_action('woocommerce_admin_order_data_after_order_details', 'send_order_wp_messages');
function send_order_wp_messages($order)
{
    $items = $order->get_items();
    $i = 1;
    $message = "";
    foreach ($items as $item) {
        $product_id = $item->get_product_id();
        $url = get_permalink($product_id);
        $quantity = $item->get_quantity();
        $product = $item->get_product();
        $sku = $product->get_sku();
        if (isset($item['Kesme Eni Px'])) {
            $message .= "%0aÜrün " . $i;
            $message .= "%0aSKU= " .$item['Renk SKU'];
            $message .= "%0aÜrün Linki= " . urlencode($url);
            $message .= "%0aTip= 3D Ürün";
            $message .= "%0aStil= ".$item['Renk Seçimi'];
            $message .= "%0aAdet= ".$quantity;
            $message .= "%0aKesilmiş Resim= " . urlencode($item["Resim URL"]);
            $message .= "%0aEn (cm)= " . $item["En cm"];
            $message .= "%0aBoy (cm)= " . $item["Boy cm"];
            if (isset($item['Tip'])) {
                $message .= "%0aÖrnek Ürün";
            }
        } else {
            $message .= "%0aÜrün " . $i;
            $message .= "%0aSKU= " .$sku;
            $message .= "%0aÜrün Linki= " . urlencode($url);
            $message .= "%0aTip= Normal Ürün";
            $message .= "%0aAdet= ".$quantity;
        }

        $i += 1;
    }
    echo '<a target="_blank" href="https://wa.me/?text=' . $message . '">Bilgileri Whatsapp ile Gönder</a>';
}

add_action('woocommerce_checkout_create_order_line_item', 'save_cart_item_custom_meta_as_order_item_meta', 10, 4);
function save_cart_item_custom_meta_as_order_item_meta($item, $cart_item_key, $values, $order)
{
    $meta_key = 'Pozisyon X';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Pozisyon Y';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Kesme Eni Px';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Kesme Boyu Px';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'En cm';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Boy cm';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Tip';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Malzeme Adı';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Renk Seçimi';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Renk SKU';
    if (isset($values[$meta_key])) {
        $item->update_meta_data($meta_key, $values[$meta_key]);
    }
    $meta_key = 'Resim URL';
    if (isset($values[$meta_key])) {
        $photo_url = $values[$meta_key];

        $targ_w = $item['Kesme Eni Px'];
        $targ_h = $item['Kesme Boyu Px'];
        $targ_pos_x = $item['Pozisyon X'];
        $targ_pos_y = $item['Pozisyon Y'];

        $src = $photo_url;
        $image = openImage($src);
        $cropped_image = my_crop($image, $targ_pos_x, $targ_pos_y, $targ_w, $targ_h);
        $item->update_meta_data("Resim URL", save_image($cropped_image));
    }
}

add_action('woocommerce_thankyou', 'thankyou', 10, 1);
function thankyou($order_id)
{
    $order = wc_get_order($order_id);

    $user = $order->get_user();

    if ($user) {
        $user->remove_role('sample-product');
    }
}

add_action('woocommerce_order_status_completed', 'action_on_order_status_completed', 20, 2);
function action_on_order_status_completed($order_id, $order)
{
    $items = $order->get_items();
    $example_product_count = 0;
    $user = $order->get_user();
    if ($user) {

        foreach ($items as $item) {
            if ($item->get_meta('Tip')) {
                $example_product_count += 1;
            }
        }

        if ($example_product_count >= 1) {
            $user->add_role('sample-product');
        } else {
            $user->remove_role('sample-product');
        }
    }
}

function append_sku_string($link, $post)
{
    $post_meta = get_post_meta($post->ID, '_sku', true);
    if ('product' == get_post_type($post)) {
        $link = $link . '#' . $post_meta;
        return $link;
    }
}

add_filter('post_type_link', 'append_sku_string', 1, 2);


function wpdocs_redirect_after_logout()
{
    $current_user = wp_get_current_user();
    $role_name = $current_user->roles[0];
    if ('subscriber' === $role_name) {
        $redirect_url = site_url();
        wp_safe_redirect($redirect_url);
        exit;
    }

}

add_action('wp_logout', 'wpdocs_redirect_after_logout');

function load_stylesheets()
{
    wp_enqueue_style('styles', get_template_directory_uri() . "/scss/main.css", array(), 1, 'all');
    wp_enqueue_script('ajax', "https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js", array(), 1);
    wp_localize_script('ajax', 'my_ajax_object',
        array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_script('bootstrapjs', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array(), null);
    wp_enqueue_style('checkout-style', get_template_directory_uri() . "/css/checkout-page.css", array(), false);

}


function add_js()
{
    wp_enqueue_script('jquery_footer', "https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js", array(), false, 1);
    wp_enqueue_script('jquery_magnify_footer', "https://cdnjs.cloudflare.com/ajax/libs/magnify/2.3.3/js/jquery.magnify.min.js", array(), false, 1);
    //wp_enqueue_script('lightbox-footer', "https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js", array(), false, 1);
    wp_enqueue_script('masonry_desandro', "https://masonry.desandro.com/masonry.pkgd.js", array(), false, 1);
    wp_enqueue_script('bootstrap_footer', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js", array(), false, 1);
    wp_enqueue_script('slick', get_template_directory_uri() . "/node_modules/slick-carousel/slick/slick.min.js", array(), false, 1);
    wp_enqueue_script('masonry_js', get_template_directory_uri() . "/js/masonry.js", array(), false, 1);

    //wp_enqueue_script('scrollbar', get_template_directory_uri() . "/js/scrollbar.js", array(), false, 1);
    wp_enqueue_script('stick-menu', get_template_directory_uri() . "/js/stickmenu.js", array(), false, 1);
    wp_enqueue_script('croppers', get_template_directory_uri() . "/js/cropper-min.js", array(), false, 1);
    wp_enqueue_script('image-cropper', get_template_directory_uri() . "/js/image-cropper-min.js", array(), false, 1);
    wp_enqueue_script('tab', get_template_directory_uri() . "/js/tab.js", array(), false, 1);
    wp_enqueue_script('masked-input', get_template_directory_uri() . "/js/jquery.maskedinput.js", array(), false, 1);
    wp_enqueue_script('inputmask', get_template_directory_uri() . "/js/inputmask.js", array(), false, 1);
    wp_enqueue_script('product-slider', get_template_directory_uri() . "/js/product-slider-min.js", array(), false, 1);

    if (is_front_page()) {
        wp_enqueue_script('header_min', get_template_directory_uri() . "/js/header-min.js", array(), false, 1);
    }

}

add_theme_support('post-thumbnails');
add_theme_support('woocommerce');
add_action('wp_enqueue_scripts', 'load_stylesheets');
add_action('wp_enqueue_scripts', 'add_js');
add_filter('use_block_editor_for_post_type', '__return_false');
add_action('wp_footer', 'add_front_page_footer_script');
//add_filter('woocommerce_enqueue_styles', '__return_false');

