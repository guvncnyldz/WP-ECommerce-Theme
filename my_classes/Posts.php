<?php


function get_products($orderby, $order, $limit, $category_name = '')
{
    if ($category_name == '') {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'meta_key' => 'total_sales',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                ),
                array(
                    'key' => '_backorders',
                    'value' => 'no'
                ),
                array(
                    'key' => 'total_sales'
                )),
            'order' => $order);
    } else {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'product_cat' => $category_name,
            'post_status' => 'publish',
            'meta_key' => 'total_sales',
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                ),
                array(
                    'key' => '_backorders',
                    'value' => 'no'
                ),
                array(
                    'key' => 'total_sales'
                )),
            'orderby' => $orderby,
            'order' => $order);
    }

    $loop = new WP_Query($args);

    return $loop;
}

function my_get_product_thumbnail_url($product_id, $size = 'shop_catalog')
{
    $image_size = apply_filters('single_product_archive_thumbnail_size', $size);
    return get_the_post_thumbnail_url($product_id, $image_size);
}

function get_posts_all($orderby, $order, $limit, $category_name = '')
{
    if ($category_name == '') {
        $args = array(
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'order' => $order);
    } else {
        $args = array(
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'cat' => $category_name,
            'orderby' => $orderby,
            'order' => $order);
    }

    $loop = new WP_Query($args);

    return $loop;
}