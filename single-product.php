<?php

global $post;
$id = $post->ID;
$product = wc_get_product($post->ID);
$current_cat = get_the_terms($post->ID, 'product_cat');
$parent = get_term($current_cat[0]->parent, 'product_cat');

if ($parent->slug == '3d_duvar_posterleri') {
    $file = 'single-product-3d_duvar_posterleri.php';
} else if (has_term('3d_duvar_posterleri', 'product_cat')) {
    $file = 'single-product-3d_duvar_posterleri.php';
} else {
    $file = 'single-product-default.php';
}

global $woocommerce;

if ($overridden_template = locate_template($file))
    load_template($overridden_template);