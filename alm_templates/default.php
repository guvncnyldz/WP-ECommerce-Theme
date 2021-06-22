<?php

global $product;
if ($product->get_status() != 'publish' || $product->get_stock_status() != 'instock') return;
$tags = get_the_terms(get_the_ID(), 'product_tag');
echo '<div class="col-md-4 col-lg-4 col-6">';
echo '<div class="product-view">';
echo '<div class="action-container">';
echo '<ul class="product-actions">';
echo '<li>
                                        ' . get_favorites_button() . '
                                </li>';
echo '<li>';
echo '<a href="' . get_permalink() . '">';
echo '<img src="' . get_template_directory_uri() . '/img/icon/g-shopping.svg" alt="">';
echo '</a>';
echo '</li>';
echo '<li>';
echo '<a href="' . get_the_post_thumbnail_url(get_the_ID()).'" data-toggle="lightbox2" data-gallery="gallery">
<img src="' . get_template_directory_uri() . '/img/icon/g-detail.svg" alt="">
</a>';
echo '</li>';
echo '</ul>';
echo '</div>';
echo '<a href="' . get_permalink() . '" class="product">';
echo '<ul class="tags">';
echo '<li>YENİ</li>';
echo '</ul>';
echo '<img class="img-fluid" src="' . get_the_post_thumbnail_url(get_the_ID()) . '">';
echo '<h5>' . get_the_title() . '</h5>';
echo '<ul class="price">';
if ($product->get_sale_price()) {
    echo '<li>' . $product->get_sale_price(). get_woocommerce_currency_symbol().'</li>';
    echo '<li>' . $product->get_regular_price(). get_woocommerce_currency_symbol().'</li>';
} else if ($product->get_regular_price()) {
    echo '<li>' . $product->get_regular_price() . get_woocommerce_currency_symbol().'</li>';
    echo '<li></li>';
}
echo '</ul>';
echo '</a></div></div>';