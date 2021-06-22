<?php

function get_comments_all($orderby, $order, $limit)
{
    $args = array(
        'posts_per_page' => $limit,
        'status' => 'approve',
        'order' => $order,
        'orderby' => $orderby);


    $comments = get_comments($args);

    return $comments;
}

function get_comments_post($post_id)
{
    $args = array(
        'status' => 'approve',
        'order' => 'DESC',
        'post_id' => $post_id,
        'orderby' => 'date');


    $comments = get_comments($args);

    return $comments;
}

function add_comment($content,$user_id,$post_id)
{
    $args = array(
        'comment_content' => $content,
        'user_id' => $user_id,
        'comment_approved' => 0,
        'comment_post_ID' => $post_id);


    wp_insert_comment($args);
}