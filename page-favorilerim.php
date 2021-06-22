<?php
/*
 * Template Name: My Favorites
 */
get_header();
?>
<div class="container">
    <ul class="breadcrumb">
        <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
        <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
        <li><a class="disable" href="#">Favorilerim</a></li>
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

                        if (!$first_name && !$last_name) {
                            $first_name = "Misafir";
                        }
                        ?>
                        <h5><?php echo $first_name . " " . $last_name ?></h5>
                    </div>
                    <ul>
                        <li><a href="/siparislerim">Siparişlerim</a></li>
                        <li><a href="/adreslerim">Adreslerim</a></li>
                        <li class="active"><a href="/favorilerim">Favorilerim</a></li>
                        <li><a href="/promosyonlarim">İndirim Kuponlarım</a></li>
                        <li><a href="/hesabim">Kullanıcı Bilgilerim</a></li>
                        <li><a href="<?php echo wp_logout_url(home_url()) ?>">Çıkış Yap</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-8">
                <h5>Favorilerim</h5>
                <div class="my-favorite">
                    <div class="row">
                        <?php
                        $products = get_user_favorites(get_current_user_id());

                        if (sizeof($products) > 0) {

                            foreach ($products as $product_id) {
                                $product = wc_get_product($product_id);

                                if ($product->get_status() != 'publish' || $product->get_stock_status() != 'instock') return;
                                $tags = get_the_terms($product->get_id(), 'product_tag');
                                echo '<div class="col-md-4 col-lg-4 col-6">';
                                echo '<div class="product-view">';
                                echo '<div class="action-container">';
                                echo '<ul class="product-actions">';
                                echo '<li>
                                        ' . get_favorites_button($product->get_id()) . '
                                </li>';
                                echo '<li>';
                                echo '<a href="' . get_permalink($product->get_id()) . '">';
                                echo '<img src="' . get_template_directory_uri() . '/img/icon/g-shopping.svg" alt="">';
                                echo '</a>';
                                echo '</li>';
                                echo '<li>';
                                echo '<a href="' . get_the_post_thumbnail_url($product->get_id()) . '" data-toggle="lightbox2" data-gallery="gallery">
<img src="' . get_template_directory_uri() . '/img/icon/g-detail.svg" alt="">
</a>';
                                echo '</li>';
                                echo '</ul>';
                                echo '</div>';
                                echo '<a href="' . get_permalink($product->get_id()) . '" class="product">';
                                echo '<ul class="tags">';
                                echo '<li>YENİ</li>';
                                echo '</ul>';
                                echo '<img class="img-fluid" src="' . get_the_post_thumbnail_url($product->get_id()) . '">';
                                echo '<h5>' . $product->get_title() . '</h5>';
                                /*                                    echo '<ul class="price">';
                                                                    if ($product->get_sale_price()) {
                                                                        echo '<li>' . $product->get_sale_price() . ' ₺</li>';
                                                                        echo '<li>' . $product->get_regular_price() . ' ₺</li>';
                                                                    } else if ($product->get_regular_price()) {
                                                                        echo '<li>' . $product->get_regular_price() . ' ₺</li>';
                                                                        echo '<li></li>';
                                                                    } else {
                                                                        if($product->get_default_attributes())
                                                                        {
                                                                            foreach($product->get_available_variations() as $pav){
                                                                                $def=true;
                                                                                foreach($product->get_variation_default_attributes() as $defkey=>$defval){
                                                                                    if($pav['attributes']['attribute_'.$defkey]!=$defval){
                                                                                        $def=false;
                                                                                    }
                                                                                }
                                                                                if($def){
                                                                                    $display_price = $pav['display_price'];
                                                                                    $regular_price = $pav['display_regular_price'];
                                                                                    break;
                                                                                }
                                                                                if($def)
                                                                                {
                                                                                    break;
                                                                                }
                                                                            }

                                                                            if ($display_price != $regular_price) {
                                                                                echo '<li>' . $display_price. ' ₺</li>';
                                                                                echo '<li>' . $regular_price . ' ₺</li>';
                                                                            } else{
                                                                                echo '<li>' . $display_price . ' ₺</li>';
                                                                                echo '<li></li>';
                                                                            }
                                                                        }
                                                                    }
                                                                    echo '</ul>';*/
                                echo '</a></div></div>';
                            }
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js'></script>
<script src="./script.js"></script>
<script>
    $(document).on("click", '[data-toggle="lightbox2"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true,
            showArrows: false
        });
    });
</script>
