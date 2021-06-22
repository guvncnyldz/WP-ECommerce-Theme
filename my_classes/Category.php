<?php

include "wp-content/themes/duvarkagidimarketi/my_models/CategoryModel.php";

function get_categories_hierarchically($taxonomy = null, $parent = 0): CategoryModel
{
    $args = array(
        'taxonomy' => 'product_cat',
        'title_li' => '',
        'hide_empty' => 0,
        'parent' => $parent,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    $response = get_categories($args);

    $origin_category = new CategoryModel();
    $origin_category->cat = $response[0];
    $origin_category->subcategories = get_subcategories($origin_category->cat->cat_ID, $taxonomy,'menu_order','asc');

    return $origin_category;
}

function get_subcategories($parent, $taxonomy = null,$orderby = '',$order = ''): array
{
    $categories = array();

    $args = array(
        'taxonomy' => $taxonomy,
        'title_li' => '',
        'hide_empty' => 0,
        'parent' => $parent,
        'orderby' => $orderby,
        'order' => $order
    );

    $current_categories = get_categories($args);

    foreach ($current_categories as $cat) {
        $category = new CategoryModel();
        $category->cat = $cat;
        array_push($categories, $category);
        $category->subcategories = get_subcategories($cat->cat_ID, $taxonomy,$orderby,$order);
    }

    return $categories;
}

function get_all_childless($taxonomy = 'category', $orderby = '', $order = '', $limit = 0, $hide_empty = 0): array
{
    $categories = array();

    $args = array(
        'taxonomy' => $taxonomy,
        'hide_empty' => $hide_empty,
        'orderby' => $orderby,
        'order' => $order,
        'number' => $limit,
        'childless' => true,
    );

    $subcategories = get_categories($args);

    foreach ($subcategories as $cat) {
        $category = new CategoryModel();
        $category->cat = $cat;
        array_push($categories, $category);
    }

    return $categories;
}
