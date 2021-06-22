<?php get_header('front'); ?>
<?php

?>
<!-- Category Slide  -->

<div class="container-fluid">
    <div class="container">
        <!-- Header -->
        <div class="sectionHeader ml-10">
            <div class="sectionTitle">
                <h6>KATEGORİLER</h6>
                <h3>Popüler Olanlar</h3>
            </div>
            <div class="arrows">
                <button class="next-arrow">
                    <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg">
                </button>
                <button class="prev-arrow">
                    <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg">
                </button>
            </div>
        </div>

        <section class="categorySlide slider row">
            <?php $childless_categories = get_all_childless('product_cat', 'count', 'DESC', 6);
            foreach ($childless_categories as $cat) {
                echo '<div>';
                echo '<div class="categoryCard">';
                echo '<div class="content">';
                echo '<h4>' . $cat->cat->name . '</h4>';
                echo '<a class="ui-button--thirdy ui-button-size--small" href="' . get_term_link($cat->cat->term_id) . '">Ürünleri Görüntüle</a>';
                echo '</div>';
                $thumbnail_id = get_term_meta($cat->cat->term_id, 'thumbnail_id', true);
                $image = wp_get_attachment_url($thumbnail_id, 'large');
                echo '<img src="' . $image . '" alt="">';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </section>
    </div>
</div>

<!-- Products -->
<div class="container">
    <div class="sectionHeader mb-88">
        <div class="sectionTitle">
            <h6>ÜRÜNLER</h6>
            <h3>İlginizi Çekebilir</h3>
        </div>
    </div>
    <ul class="tab-list text-right" role="tablist">
        <li class="active">
            <a href="#right-tab1" role="tab" data-toggle="tab">Son Eklenenler</a>
        </li>
        <li role="presentation">
            <a href="#right-tab2" role="tab" data-toggle="tab">Çok Satanlar</a>
        </li>
        <li role="presentation">
            <a href="#right-tab3" role="tab" data-toggle="tab">Fırsat Ürünleri</a>
        </li>
    </ul>


    <div class="tab-content">
        <div id="right-tab1" role="tabpanel" class="tab-pane active">
            <div class="row">

                <?php
                $loop = get_products('date', 'desc', 8);
                while ($loop->have_posts()) {
                    $loop->the_post();
                    global $product;
                    $tags = get_the_terms(get_the_ID(), 'product_tag');
                    echo '<div class="col-md-3">';
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
                    echo '<ul class="tags">';
                    echo '<li>YENİ</li>';
                    echo '</ul>';
                    echo '<img class="img-fluid" src="' . get_the_post_thumbnail_url() . '">';
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

                wp_reset_query();
                ?>
            </div>
        </div>
        <div id="right-tab2" role="tabpanel" class="tab-pane">
            <div class="row">
                <!-- Product -->
                <?php
                $loop = get_products('meta_value_num', 'desc', 4);

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
                    echo '<ul class="tags">';
                    echo '<li>YENİ</li>';
                    echo '</ul>';
                    echo '<img class="img-fluid" src="' . get_the_post_thumbnail_url() . '">';

                    echo '<h5>' . get_the_title() . '</h5>';
                    echo '<ul class="price">';
                    if ($product->get_sale_price()) {
                        echo '<li>' . $product->get_sale_price() . get_woocommerce_currency_symbol().'</li>';
                        echo '<li>' . $product->get_regular_price() . get_woocommerce_currency_symbol().'</li>';
                    } else if ($product->get_regular_price()) {
                        echo '<li>' . $product->get_regular_price(). get_woocommerce_currency_symbol().'</li>';
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
                                echo '<li>' . $display_price. get_woocommerce_currency_symbol().'</li>';
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

                wp_reset_query();
                ?>
            </div>
        </div>
        <div id="right-tab3" role="tabpanel" class="tab-pane">
            <div class="row">
                <?php

                $loop = get_products('', '', 4, 'İNDİRİMLİ ÜRÜNLER');

                while ($loop->have_posts()) {
                    $loop->the_post();
                    global $product;
                    echo '<div class="col-md-3">';
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
                    echo '<ul class="tags">';
                    echo '<li>YENİ</li>';
                    echo '</ul>';
                    echo '<img class="img-fluid" src="' . get_the_post_thumbnail_url() . '">';

                    echo '<h5>' . get_the_title() . '</h5>';
                    echo '<ul class="price">';
                    if ($product->get_sale_price()) {
                        echo '<li>' . $product->get_sale_price() . get_woocommerce_currency_symbol().'</li>';
                        echo '<li>' . $product->get_regular_price() . get_woocommerce_currency_symbol().'</li>';
                    } else if ($product->get_regular_price()) {
                        echo '<li>' . $product->get_regular_price(). get_woocommerce_currency_symbol().'</li>';
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

                wp_reset_query();
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Commnets -->
<div class="container-fluid">
    <?php $musteri_yorumlari = get_field('musteri_yorumlari'); ?>
    <div class="row">
        <div class="comments">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sectionTitle">
                            <h6><?php echo $musteri_yorumlari['kucuk_baslik'] ?></h6>
                            <h3><?php echo $musteri_yorumlari['buyuk_baslik'] ?></h3>
                            <p><?php echo $musteri_yorumlari['aciklama'] ?></p>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="masonry-grid animated">
                            <div class="grid">
                                <?php
                                $comments = get_comments_all('', '', 10);

                                foreach ($comments as $comment) {
                                    $meta = get_user_meta($comment->user_id);

                                    $first_name = $meta['first_name'][0];
                                    $last_name = $meta['last_name'][0];
                                    $user = get_userdata($comment->user_id);
                                    echo '<a href="' . get_permalink($comment->comment_post_ID) . '">';
                                    echo '<div class="grid-item grid-item--width2">';
                                    echo '<img src="' . get_template_directory_uri() . '/img/icon/comment-tick.svg" alt="">';
                                    echo '<p>' . $comment->comment_content . '</p>';
                                    echo '<span><i></i>' . $first_name . " " . $last_name . '</span>';
                                    echo '</div>';
                                    echo '</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Instagram -->
<div class="container">
    <?php echo do_shortcode('[instagram-feed]'); ?>
</div>

<!-- Blog -->
<div class="container-fluid">
    <div class="blog">
        <div class="container">
            <div class="sectionTitle">
                <h6>BLOG</h6>
                <h3>Yazılarımız</h3>
            </div>
            <!-- Tab links -->

            <?php
            echo '<div class="tab">';
            $isFirst = true;

            $categories = get_categories(array("taxonomy" => 'category', "hide_empty" => 0, "hierarchy" => 1));

            foreach ($categories as $category) {
                if ($isFirst) {
                    $isFirst = false;
                    echo '<button class="tablinks active" onclick="openCategory(event,  ' . $category->term_id . ')">' . $category->name . '</button>';
                } else {
                    echo '<button class="tablinks" onclick="openCategory(event,' . $category->term_id . ')">' . $category->name . '</button>';
                }
            }

            $isFirst = true;
            foreach ($categories as $category) {
                $posts = get_posts_all('date', 'DESC', 5, $category->cat_ID);
                $postcount = 1;

                echo '</div>';
                if ($isFirst) {
                    $isFirst = false;
                    echo '<div id="' . $category->term_id . '" class="tabcontent" style="display: block;">';
                } else
                    echo '<div id="' . $category->term_id . '" class="tabcontent" style="display: none;">';
                echo '<div class="row">';
                if ($posts->have_posts()) {
                    while ($posts->have_posts()) {
                        $posts->the_post();
                        global $post;

                        $tags = get_the_tags($post->ID);

                        if ($post->post_count <= 2) {
                            if ($postcount == 1) {
                                echo '<div class="col-md-8">';
                            } else {
                                echo '<div class="col-md-4">';
                            }

                            echo '<div class="blog-post">';
                            echo '<div class="img-hover-zoom">';
                            echo '<a class="blog-post-a" href="' . get_the_permalink($post->ID) . '">';
                            echo '<img src="' . the_post_thumbnail($post->ID, 'shop_thumbnail') . '" alt="">';
                            echo '</a>';
                            echo '</div>';
                            echo '<div class="content">';
                            echo '<span>' . wp_date('j F Y', strtotime($post->post_date)) . '</span>';
                            echo '<a class="blog-post-a" href="' . get_the_permalink($post->ID) . '">';
                            echo '<h4>' . $post->post_title;
                            echo '</a>';
                                echo '<i>YENİ</i>';
                            echo ' </h4>';
                            echo '<p>' . $post->post_excerpt . '</p>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';

                        } else {
                            if ($postcount == 1) {
                                echo '<div class="col-md-8">';
                            } else if ($postcount == 2) {
                                echo ' <div class="row">';
                                echo '<div class="col-md-6">';
                            } else if ($postcount == 3) {
                                echo '<div class="col-md-6">';
                            } else if ($postcount == 4) {
                                echo '<div class="col-md-4">';
                            }

                            echo '<div class="blog-post">';
                            echo '<div class="img-hover-zoom">';
                            echo '<a class="blog-post-a" href="' . get_the_permalink($post->ID) . '">';
                            echo '<img src="' . the_post_thumbnail($post->ID, 'shop_thumbnail') . '" alt="">';
                            echo '</a>';
                            echo '</div>';
                            echo '<div class="content">';
                            echo '<span>' . wp_date('j F Y', strtotime($post->post_date)) . '</span>';
                            echo '<a class="blog-post-a" href="' . get_the_permalink($post->ID) . '">';
                            echo '<h4>' . $post->post_title;
                            echo '</a>';
                                    echo '<i>YENİ</i>';
                            echo ' </h4>';
                            echo '<p>' . $post->post_excerpt . '</p>';
                            echo '</div>';
                            echo '</div>';

                            if ($postcount == 3) {
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            } else if ($postcount == 4 && $posts->post_count == 4) {
                                echo '</div>';
                            } else if ($postcount == 5) {
                                echo '</div>';
                            }
                        }

                        $postcount += 1;
                    }

                }
                echo '<div class="col-md-12">
                                        <div class="full-link">
                                            <a class="ui-button-size--small ui-button--secondary-outline" href="' . get_category_link($category->cat_ID) . '">Tüm Blog Yazılarını Görüntüle</a>
                                        </div>
                                      </div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
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