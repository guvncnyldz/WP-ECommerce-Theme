<?php

function get_pages_all($orderby, $order, $limit = -1, $category_name = '')
{
    $frontpage_id = get_option( 'page_on_front' );

    $args = array(
        'post_type' => 'page',
        'posts_per_page' => $limit,
        'post_parent' => $frontpage_id,
        'orderby' => $orderby,
        'status' => 'publish',
        'order' => $order);

    $loop = new WP_Query($args);

    return $loop;
}