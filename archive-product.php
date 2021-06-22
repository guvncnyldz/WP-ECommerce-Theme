<?php get_header();

$search = false;
if (isset($_GET['s'])) {
    $search = true;
}
console_log("test");
if(is_product_category())
    console_log("öyle");
else
    console_log("değil");

?>
<div class="container">
    <ul class="breadcrumb bottom-line">
        <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
        <?php
        $categories = [];
        $current_cat = get_queried_object();
        if ($search) {
            echo '<li><img src="' . get_template_directory_uri() . '/img/icon/b-angle-small-right.svg" alt=""></li>';
            echo '<li><a class="disable" href="">Arama</a></li>';
        } else {

            array_push($categories, $current_cat);

            $cat = $current_cat;
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
        }
        ?>
    </ul>
</div>

<div class="container">
    <div class="product-list">
        <div class="row">
            <?php
            $other_categories = get_subcategories($categories[0]->term_id, 'product_cat');

            if (!$other_categories) {
                $other_categories = get_subcategories($categories[1]->term_id, 'product_cat');
            }

            if (!$search && $other_categories && $other_categories[0]->cat->name != 'Genel') {
                echo '<div class="col-md-3"><ul id="menu-float" class="categoryList">';
                foreach ($other_categories as $category) {
                    echo '<li>';
                    echo '<a href = "' . get_category_link($category->cat->cat_ID) . '">';
                    echo '<span>' . $category->cat->name . '</span>';
                    echo '<img src=' . get_template_directory_uri() . '/img/icon/b-link-arrow.svg" alt="">';
                    echo '</a>';
                    echo '</li>';
                }
                echo '</ul></div>';
            } else if(!$search) {
                echo '<div class="col-md-3"><ul id="menu-float" class="categoryList">';
                echo '<li>';
                echo '<a href = "' . get_category_link($current_cat->cat_ID) . '">';
                echo '<span>' . $current_cat->name . '</span>';
                echo '<img src=' . get_template_directory_uri() . '/img/icon/b-link-arrow.svg" alt="">';
                echo '</a>';
                echo '</li>';
                echo '</ul></div>';
            } ?>
            <?php if ($search) {
                echo '<div class="col-md-12">';
            } else {
                echo '<div class="col-md-9">';
            } ?>
            <div class="sectionTitle bottom-32">
                <h6><?php echo $categories[1]->name ?></h6>

                <?php
                if ($search) {
                    echo '<h3><b>"' . $_GET['s'] . '"</b> ile ilgili arama sonuçları</h3>';
                }
                ?>
                <h3><?php echo $categories[0]->name ?></h3>
            </div>
            <div class="row">
                <?php if ($search) {
                    while (have_posts()) {
                        the_post();
                        global $product;
                        if ($product->get_status() != 'publish' || $product->get_stock_status() != 'instock') continue;
                        $tags = get_the_terms(get_the_ID(), 'product_tag');
                        echo '<div class="col-md-3 col-lg-3 col-6">';
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
                        echo '<a href="' . get_the_post_thumbnail_url(get_the_ID()) . '" data-toggle="lightbox2" data-gallery="gallery">
<img src="' . get_template_directory_uri() . '/img/icon/g-detail.svg" alt="">
</a>';
                        echo '</li>';
                        echo '</ul>';
                        echo '</div>';
                        echo '<a href="' . get_permalink() . '" class="product">';
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
                        echo '</a></div></div>';
                    }
                } else {

                    echo do_shortcode('[ajax_load_more woocommerce="true" posts_per_page="12" loading_style="infinite skype" transition_container_classes="row"]');
                } ?>
                <script>
                    $("#ajax-load-more").removeClass("woocommerce")
                </script>
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