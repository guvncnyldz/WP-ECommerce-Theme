<?php get_header() ?>

<?php
global $post;
$id = $post->ID;
$product = wc_get_product($post->ID);

if (isset($_POST['comment'])) {
    add_comment($_POST['comment_content'], get_current_user_id(), $id);
}


?>
</div>
<div class="container">
    <ul class="breadcrumb bottom-line">
        <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
        <?php

        $categories = [];
        $current_cat = get_the_terms($post->ID, 'product_cat');
        array_push($categories, $current_cat[0]);

        $cat = $current_cat[0];
        while ($cat->parent) {
            $cat = get_term($cat->parent, 'product_cat');

            if ($cat->parent)
                array_push($categories, $cat);
        }
        for ($i = sizeof($categories); $i >= 0; $i--) {
            echo '<li><a href="' . get_category_link($categories[$i]->term_id) . '">' . $categories[$i]->name . '</a></li>';

            if ($i != 0)
                echo '<li><img src="' . get_template_directory_uri() . '/img/icon/b-angle-small-right.svg" alt=""></li>';
        }
        echo '<li><img src="' . get_template_directory_uri() . '/img/icon/b-angle-small-right.svg" alt=""></li>';
        echo '<li><a class="disable">' . $product->name . '</a></li>';
        ?>
    </ul>
</div>

<div class="product">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="product-slider">
                    <?php
                    $attachment_ids = $product->get_gallery_image_ids();
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID());
                    echo '<div class="cSlider cSlider--single">';
                    foreach ($attachment_ids as $attachment_id) {
                        $Original_image_url = wp_get_attachment_url($attachment_id);
                        echo '<div class="cSlider__item">
                            <img src="' . wp_get_attachment_image($attachment_id, 'full') . '
                        </div>';
                    }

                    echo '</div>';
                    echo '<div class="cSlider cSlider--nav">';
                    echo '<div class="cSlider__item">
                            <img src="' . $thumbnail . '"></div>';
                    foreach ($attachment_ids as $attachment_id) {
                        $Original_image_url = wp_get_attachment_url($attachment_id);
                        echo '<div class="cSlider__item">
                            <img src="' . wp_get_attachment_image($attachment_id, 'full') . '
                        </div>';
                    }
                    echo '</div>';
                    ?>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Title -->
                <div class="product-title">
                    <h5><?php echo $current_cat[0]->name ?></h5>
                    <h1>
                        <?php echo $product->name ?>
                        <?php $tags = get_the_terms($post->ID, 'product_tag');
                        echo '<a href="#">YENİ</a>';

                        ?>
                    </h1>
                </div>

                <!-- Detail -->
                <ul class="product-detail">
                    <li class="product-actions">
                        <a href="#">
                            <img src="<?php echo bloginfo('template_url') ?>/img/icon/b-share.svg" alt="">
                            Ürünü Paylaş
                        </a>
                        <style>
                            .fake-favorites-container {
                                display: flex;
                                flex-direction: row;
                            }
                        </style>
                        <div class="fake-favorites-container">
                            <?php echo get_favorites_button() ?>
                        </div>
                        <script>
                            $(".simplefavorite-button").click(() => {
                                if (!$(".simplefavorite-button").hasClass('active')) {
                                    $(".favorites-text").remove()
                                    $(".fake-favorites-container").append("<div class='favorites-text'>Favorilerinize Eklendi!</div>")
                                } else {
                                    $(".favorites-text").remove()
                                    $(".fake-favorites-container").append("<div class='favorites-text'>Favorilerine Ekle</div>")
                                }
                            })
                            if ($(".simplefavorite-button").hasClass('active')) {
                                $(".fake-favorites-container").append("<div class='favorites-text'>Favorilerinize Eklendi!</div>")
                            } else {
                                $(".fake-favorites-container").append("<div class='favorites-text'>Favorilerine Ekle</div>")
                            }
                        </script>
                    </li>
                </ul>

                <!-- Pricing -->
                <div class="product-footer">
                    <div class="price">
                        <?php
                        echo '<span>Ürün Fiyatı</span>';
                        if ($product->get_sale_price()) {
                            echo '<h4>' . $product->get_sale_price() . get_woocommerce_currency_symbol().'</h4>';
                        } else {
                            echo '<h4>' . $product->get_regular_price() . get_woocommerce_currency_symbol().'</h4>';
                        }

                        ?>
                    </div>
                    <form method="post" action="<?php echo wc_get_cart_url() ?>">
                        <input type="number" name="quantity" value="1"/>
                        <input type="hidden" name="product_id" value="<?php echo $product->id ?>"/>
                        <?php if ($product->get_status() == 'publish' && $product->get_stock_status() == 'instock'): ?>
                        <button type="submit" name="add_to_cart">
                            <img src="<?php echo bloginfo('template_url') ?>/img/icon/w-shopping.svg" alt="">
                            Sepete Ekle
                        </button>
                        <?php else: ?>
                        <button type="submit" disabled name="add_to_cart">
                            Stokta Yok
                        </button>
                        <?php endif;?>
                    </form>
                </div>

                <ul class="product-detail-list">
                    <?php
                    $attributes = $product->get_attributes();
                    console_log($attributes);
                    echo '<li><h2>Ürün Bilgileri</h2></li>';
                    foreach ($attributes as $attribute) {
                        if(!$attribute->get_visible())
                            continue;

                        echo '<li>';
                        echo '<span>' . $attribute->get_name() . '</span>';
                        echo '<b>';
                        foreach ($attribute->get_options() as $option)
                            echo $option . " ";
                        echo '</b>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Shopping Brand -->
<div class="container">
    <div class="shopping-brand">
        <div class="row">
            <div class="col-md-3 col-lg-3 col-6">
                <img class="img-fluid" src="<?php bloginfo('template_url') ?>/img/brand-cargo.svg" alt="">
            </div>
            <div class="col-md-3 col-lg-3 col-6">
                <img class="img-fluid" src="<?php bloginfo('template_url') ?>/img/brand-shipping.svg" alt="">
            </div>
            <div class="col-md-3 col-lg-3 col-6">
                <img class="img-fluid" src="<?php bloginfo('template_url') ?>/img/brand-customize.svg" alt="">
            </div>
            <div class="col-md-3 col-lg-3 col-6">
                <img class="img-fluid" src="<?php bloginfo('template_url') ?>/img/brand-payment.svg" alt="">
            </div>
        </div>
    </div>
</div>

<!-- Product Tabs -->
<div class="product-tabs">
    <div class="container">
        <ul class="tab-list" role="tablist">
            <li class="active">
                <a href="#left-tab1" role="tab" data-toggle="tab">Ürün Bilgisi</a>
            </li>
            <li role="presentation">
                <a href="#left-tab2" role="tab" data-toggle="tab">Ürün Özellikleri</a>
            </li>
            <li role="presentation">
                <a href="#left-tab3" role="tab" data-toggle="tab">Yorumlar</a>
            </li>

            <li role="presentation">
                <a href="#left-tab4" role="tab" data-toggle="tab">Taksit Seçenekleri</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- Product Information -->
            <div id="left-tab1" role="tabpanel" class="tab-pane active">
                <div class="content-container">
                    <?php echo html_entity_decode($product->get_description()) ?>
                </div>
            </div>
            <!-- Product Features -->
            <div id="left-tab2" role="tabpanel" class="tab-pane">
                <div class="content-container">
                    <p><?php echo $product->post->post_excerpt ?></p>
                    <ul>
                        <?php
                        $attributes = $product->get_attributes();
                        console_log($attributes);
                        echo '<li><h2>Ürün Bilgileri</h2></li>';
                        foreach ($attributes as $attribute) {
                            if(!$attribute->get_visible())
                                continue;

                            echo '<li>';
                            echo '<span>' . $attribute->get_name() . '</span>';
                            echo '<b>';
                            foreach ($attribute->get_options() as $option)
                                echo $option . " ";
                            echo '</b>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <!-- Comments -->
            <div id="left-tab3" role="tabpanel" class="tab-pane">
                <?php
                $comments = get_comments_post(get_the_ID());
                foreach ($comments as $comment) {
                    $meta = get_user_meta($comment->user_id);

                    $first_name = $meta['first_name'][0];
                    $last_name = $meta['last_name'][0];
                    $user = get_userdata($comment->user_id);
                    echo '<p>' . $comment->comment_content . '</p>';
                    echo '<span><i></i>' . $first_name . " " . $last_name . '</span>';
                }
                ?>
                <?php if (is_user_logged_in()): ?>
                    <form action="" method="post">
                        <input type="text" name="comment_content">
                        <button type="submit" name="comment" value="Yorum Yap"></button>
                    </form>
                <?php else: ?>
                    <a href="/kayit-ol">Yorum Yapmak İçin Üye Olun</a>
                <?php endif ?>
            </div>
            <!-- Payment Options -->
            <div class="left-tab4" role="tabpanel" class="tab-pane">

            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="sectionHeader">
        <div class="sectionTitle">
            <h6>DİĞER ÜRÜNLERİMİZ</h6>
            <h3>Sizin İçin Seçtiklerimiz</h3>
        </div>
    </div>
    <div class="row">
        <!-- Product -->
        <?php
        $loop = get_products('meta_value_num', 'desc', 8, $current_cat[random_int(0, sizeof($current_cat))]->name);
        $colors = ['red', 'yellow', 'green', ''];

        while ($loop->have_posts()) {
            $loop->the_post();
            global $product;
            echo '<div class="col-md-3 col-lg-3 col-6">';
            echo '<div class="product-view">';
            echo '<div class="action-container">
                            <ul class="product-actions">
                                <li>
                                    ' . get_favorites_button() . '
                                </li>
                                <li>
                                    <a href="' . get_permalink() . '">
                                        <img src="' . get_template_directory_uri() . '/img/icon/g-shopping.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a href="' . get_the_post_thumbnail_url(get_the_ID()) . '" data-toggle="lightbox2" data-gallery="gallery">
                                        <img src="' . get_template_directory_uri() . '/img/icon/g-detail.svg" alt="">
                                    </a>
                                </li>
                            </ul>
                        </div>';
            echo '<a href="' . get_the_permalink() . '" class="product">';
            $tags = get_the_terms(get_the_ID(), 'product_tag');
            $random_color = $colors[rand(1, 4)];
            echo '<ul class="tags ' . $random_color . '">';
            echo '<li>YENİ</li>';
            echo '</ul>';
            echo '<img class="img-fluid" src="' . get_the_post_thumbnail_url(get_the_ID()) . '">';
            echo '<h5>' . get_the_title() . '</h5>';
            echo '<ul class="price">';
            if ($product->get_sale_price()) {
                echo '<li>' . $product->get_sale_price() . get_woocommerce_currency_symbol().'</li>';
                echo '<li>' . $product->get_regular_price() . get_woocommerce_currency_symbol().'</li>';
            } else if ($product->get_regular_price()) {
                echo '<li>' . $product->get_regular_price() . get_woocommerce_currency_symbol().'</li>';
                echo '<li></li>';
            } else {
                if ($product->get_default_attributes()) {
                    foreach ($product->get_available_variations() as $pav) {
                        $def = true;
                        foreach ($product->get_variation_default_attributes() as $defkey => $defval) {
                            if ($pav['attributes']['attribute_' . $defkey] != $defval) {
                                $def = false;
                            }
                        }
                        if ($def) {
                            $display_price = $pav['display_price'];
                            $regular_price = $pav['display_regular_price'];
                            break;
                        }
                        if ($def) {
                            break;
                        }
                    }

                    if ($display_price != $regular_price) {
                        echo '<li>' . $display_price . get_woocommerce_currency_symbol().'</li>';
                        echo '<li>' . $regular_price . get_woocommerce_currency_symbol().'</li>';
                    } else {
                        echo '<li>' . $display_price . get_woocommerce_currency_symbol().'</li>';
                        echo '<li></li>';
                    }
                }
            }
            echo '</ul>';
            echo '</a>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>


<a href="#" class="mobile-whatsapp">
    <img src="<?php bloginfo('template_url') ?>/img/icon/stick-whatsapp.svg" alt="">
</a>

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