<?php get_header() ?>

<?php
$materials = [];
global $post;
$id = $post->ID;
$product = wc_get_product($post->ID);
$extra_style_count = get_field('renk_sayisi');
$extra_style_text = get_field('renk_yazisi');
$attachment_ids = $product->get_gallery_image_ids();

foreach ($product->get_available_variations() as $variations) {
    $attribute_count = 0;
    $is_material = true;
    $material_name = "";
    foreach ($variations['attributes'] as $val => $key) {
        if (!empty($key)) {
            $attribute_count++;
            $material_name = $key;
        }

        if ($attribute_count > 1) {
            $is_material = false;
            break;
        }
    }

    if ($is_material) {
        preg_match_all('/<p[^>]*>(.*?)<\/p>/is', $variations['variation_description'], $matches);
        $material = new Material();
        $material->name = $material_name;
        $material->description = $matches[1][0];
        $material->price = $variations['display_price'];
        $material->regular = $variations['display_regular_price'];
        array_push($materials, $material);
    }
}

$current_width_cm = 100;
$current_height_cm = 100;
$current_width = 720;
$current_height = 720;
$current_pos_y = 0;
$current_pos_x = 0;
$current_material = $materials[0];
$material_index = 0;
$is_cropped = false;
$current_style_index = 0;

if (isset($_POST['width_cm']))
    $current_width_cm = $_POST['width_cm'];
if (isset($_POST['height_cm']))
    $current_height_cm = $_POST['height_cm'];
if (isset($_POST['crop_height'])) {
    $is_cropped = true;
    $current_height = $_POST['crop_height'];
}
if (isset($_POST['crop_width']))
    $current_width = $_POST['crop_width'];
if (isset($_POST['crop_top']))
    $current_pos_y = $_POST['crop_top'];
if (isset($_POST['crop_left']))
    $current_pos_x = $_POST['crop_left'];
if (isset($_POST['material_choose'])) {
    $material_index = $_POST['material_choose'];
    $current_material = $materials[$material_index];
}
if (isset($_POST['color'])) {
    $current_style_index = $_POST['color'];
}
if (isset($_POST['comment'])) {
    add_comment($_POST['comment_content'], get_current_user_id(), $id);
}

$current_price = ((($current_width_cm * $current_height_cm) / 10000) * $current_material->price);
?>
</div>
<script>
    $(document).ready(function () {
        $(window).keydown(function (event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        });
    });
</script>
<style>
    .fake-a {
        width: 100%;
    }

    .fake-a-2 {
        width: 50%;
    }
</style>
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
                        <?php echo $product->name ?><span><?php echo " " . $product->get_sku() ?></span>
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

                <!-- Customize -->
                <div class="product-customize">
                    <div class="product-sizes">
                        <span>Ürün ölçülerini giriniz (cm)</span>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <img src="<?php echo bloginfo('template_url') ?>/img/icon/b-width-size.svg" alt="">
                                    <input type="number" value="<?php echo $current_width_cm ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <img src="<?php echo bloginfo('template_url') ?>/img/icon/b-height-size.svg" alt="">
                                    <input type="number" value="<?php echo $current_height_cm ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <button onclick="cropPanelOpen()" type="button" data-toggle="modal"
                                data-target="#cropImageModal">
                            <img src="<?php echo bloginfo('template_url') ?>/img/icon/b-cut.svg" alt="">
                            Duvar Kağıdını Kesmek için Tıkla
                        </button>
                        <p>Duvar kağıdınızı dilerseniz ölçülerinize göre kesebilir ve
                            kestiğiniz şekilde siparişinizi verebilirsiniz.</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="choose-material">
                                <span>Malzemesini seçin</span>
                                <button class="ui-button-size--medium select-button" type="button" data-toggle="modal"
                                        data-target="#materialModal">
                                    <span><?php echo $current_material->name ?></span>
                                    <img src="<?php echo bloginfo('template_url') ?>/img/icon/b-link-arrow.svg" alt="">
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="select-color">
                                <div class=" input-group">
                                    <label for="">Renk seçin</label>
                                    <?php
                                    echo '<select name="color" onchange="selectStyle(this)">';

                                    $i = 0;
                                    if ($current_style_index == 0)
                                        echo '<option value="' . $i . '" selected>' . $extra_style_text . "" . ($i + 1) . '</option>';
                                    else
                                        echo '<option value="' . $i . '">' . $extra_style_text . "" . ($i + 1) . '</option>';

                                    for ($i = $i + 1; $i <= $extra_style_count; $i++) {
                                        if ($current_style_index == $i)
                                            echo '<option value="' . $i . '" selected>' . $extra_style_text . "" . ($i + 1) . '</option>';
                                        else
                                            echo '<option value="' . $i . '">' . $extra_style_text . "" . ($i + 1) . '</option>';
                                    }
                                    echo '</select>';
                                    ?>
                                    <script>
                                        function eventFire(el, etype) {
                                            if (el.fireEvent) {
                                                el.fireEvent('on' + etype);
                                            } else {
                                                var evObj = document.createEvent('Events');
                                                evObj.initEvent(etype, true, false);
                                                el.dispatchEvent(evObj);
                                            }
                                        }

                                        function selectStyle(element) {
                                            index = element.value;
                                            $(".color_material").val(index)
                                            $(".color_crop").val(index)
                                            $(".color_final").val(index)
                                            if (index == 0) {
                                                child = $(".cSlider--nav .slick-track").children()[1]
                                                eventFire(child, 'click')
                                            } else {
                                                size = $(".cSlider--nav .slick-track").children().length;
                                                child = $(".cSlider--nav .slick-track").children()[(size-1) - ((element.childNodes.length - 1) - index)]
                                                eventFire(child, 'click')
                                            }
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pricing -->
                <div class="product-footer">
                    <div class="price">
                        <?php
                        echo '<span>Ürün Fiyatı</span>';
                        echo '<h4>' . $current_price . get_woocommerce_currency_symbol().'</h4>';
                        ?>
                    </div>
                    <form method="post" action="<?php echo wc_get_cart_url() ?>">
                        <input type="hidden" name="width_cm" value="<?php echo $current_width_cm ?>"/>
                        <input type="hidden" name="height_cm" value="<?php echo $current_height_cm ?>"/>
                        <input type="hidden" name="width" value="<?php echo $current_width ?>"/>
                        <input type="hidden" name="height" value="<?php echo $current_height ?>"/>
                        <input type="hidden" name="pos_x" value="<?php echo $current_pos_x ?>"/>
                        <input type="hidden" name="pos_y" value="<?php echo $current_pos_y ?>"/>
                        <input type="hidden" name="material_name" value="<?php echo $current_material->name ?>"/>
                        <input type="hidden" name="price" value="<?php echo $current_price ?>"/>
                        <input type="hidden" name="product_id" value="<?php echo $product->id ?>"/>
                        <input type="hidden" name="color" class="color_final" value="<?php echo $current_style_index ?>"/>
                        <?php if ($is_cropped && $product->get_status() == 'publish' && $product->get_stock_status() == 'instock'): ?>
                            <button type="submit" name="add_to_cart">
                                <img src="<?php echo bloginfo('template_url') ?>/img/icon/w-shopping.svg" alt="">
                                Sepete Ekle
                            </button>
                        <?php elseif($product->get_status() != 'publish' || $product->get_stock_status() != 'instock'): ?>
                            <button type="submit" name="add_to_cart">
                                Stokta Yok
                            </button>
                        <?php else: ?>
                            <button onclick="cropPanelOpen()" type="button" data-toggle="modal"
                                    data-target="#cropImageModal" name="add_to_cart">
                                <img src="<?php echo bloginfo('template_url') ?>/img/icon/b-cut.svg" alt="">
                                Kağıdı Kes
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
                <!-- Sample -->
                <div class="product-sample">
                    <h5>Örnek ürüne mi ihtiyacınız var?</h5>
                    <p>Satın almak istediğiniz üründen örnek parçalar satın alabilirsiniz.</p>
                    <form action="<?php echo wc_get_cart_url() ?>" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product->id ?>"/>
                        <button class="ui-button-size--small ui-button--secondary-outline fake-a" type="submit"
                                name="add_to_cart_sample">Örnek Satın Al
                        </button>
                    </form>
                </div>
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
                <?php echo html_entity_decode($product->get_description()) ?>
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
                    foreach ($product->get_available_variations() as $variations) {
                        $def = true;
                        foreach ($product->get_variation_default_attributes() as $defkey => $defval) {
                            if ($variations['attributes']['attribute_' . $defkey] != $defval) {
                                $def = false;
                            }
                        }
                        if ($def) {
                            $display_price = $variations['display_price'];
                            $regular_price = $variations['display_regular_price'];
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

<div class="modal fade cropImageModal" id="cropImageModal" tabindex="-1" role="dialog"
     aria-labelledby="cropImageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php bloginfo('template_url') ?>/img/icon/b-close.svg" alt="">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Mobile -->
                        <div class="modalDetail mobile">
                            <h2>Duvar Kağıdınızı Kesin</h2>
                            <p>Duvar kağıdınızı girdiğiniz ölçülere göre dilediğiniz gibi kesebilirsiniz. İhtiyaç
                                durumunda mevcut ölçülerinizi buradan tekrar değiştirebilir ve uygulayabilirsiniz.</p>
                        </div>
                        <div class="galleryCell">
                            <div class="galleryCellinner img-fluid">
                                <img id="image-main" src="<?php echo wp_get_attachment_url($attachment_ids[0]) ?>"
                                     title="" data-zoom-image="<?php bloginfo('template_url') ?>/img/dummy/dummy-3"
                                     class="crop_image">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="modalDetail desktop">
                            <h2>Duvar Kağıdınızı Kesin</h2>
                            <p>Duvar kağıdınızı girdiğiniz ölçülere göre dilediğiniz gibi kesebilirsiniz. İhtiyaç
                                durumunda mevcut ölçülerinizi buradan tekrar değiştirebilir ve uygulayabilirsiniz.</p>
                        </div>
                        <form action="" method="post" id="crop_form">
                            <div class="product-sizes">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <img onload=thumbnailLoaded(this)
                                                 src="<?php bloginfo('template_url') ?>/img/icon/b-width-size.svg"
                                                 alt="">
                                            <input name="width_cm" id="room_width" type="number"
                                                   placeholder="Genişlik (cm)"
                                                   onchange="handleChange(this);" onload="handleChange(this);"
                                                   value="<?php echo $current_width_cm ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <img src="<?php bloginfo('template_url') ?>/img/icon/b-height-size.svg"
                                                 alt="">
                                            <input name="height_cm" id="room_length" type="number"
                                                   placeholder="Yükseklik (cm)"
                                                   onchange="handleChange(this);" onload="handleChange(this);"
                                                   value="<?php echo $current_height_cm ?>">
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).ready(() => {
                                        console.log($("#room_width").val())
                                        handleChange($("#room_width"));
                                        handleChange($("#room_length"));
                                    })
                                </script>
                            </div>
                            <script>
                                real_width = 0
                                real_height = 0

                                $(".galleryCellinner #image-main").on('load', function () {
                                    img = new Image()
                                    img.src = $(".galleryCellinner #image-main").attr("src")
                                    img.onload = function () {
                                        real_width = this.width
                                        real_height = this.height
                                    }
                                })

                                $("#crop_form").submit(() => {
                                    cropper_box = $(".cropper-crop-box");
                                    cropper_container = $(".cropper-container");

                                    total_width = parseInt(cropper_container.css("width"))
                                    total_height = parseInt(cropper_container.css("height"))
                                    width = parseInt(cropper_box.css("width"))
                                    height = parseInt(cropper_box.css("height"))

                                    top_pos = parseInt(cropper_box.css("top"))
                                    left = parseInt(cropper_box.css("left"))

                                    final_width = (real_width * width) / total_width;
                                    final_height = (real_height * height) / total_height;
                                    final_left = (real_width * left) / total_width;
                                    final_top = (real_height * top_pos) / total_height;

                                    if (final_width)
                                        $("#crop_width").val(final_width);
                                    if (final_height)
                                        $("#crop_height").val(final_height);
                                    if (final_left)
                                        $("#crop_left").val(final_left);
                                    if (final_top)
                                        $("#crop_top").val(final_top);

                                    return true;
                                })
                            </script>
                            <input type="hidden" name="crop_width" id="crop_width" value="<?php echo $current_width ?>">
                            <input type="hidden" name="crop_height" id="crop_height"
                                   value="<?php echo $current_height ?>">
                            <input type="hidden" name="material_choose" value="<?php echo $material_index ?>">
                            <input type="hidden" name="crop_left" id="crop_left" value="<?php echo $current_pos_x ?>">
                            <input type="hidden" name="crop_top" id="crop_top" value="<?php echo $current_pos_y ?>">
                            <input type="hidden" name="color" class="color_material"
                                   value="<?php echo $current_style_index ?>">'

                            <div class="modalDetail desktop">
                                <span>Aynı üründen farklı ölçülerde satın alımlar gerçekleştirmek için ürününüzü sepete
                                ekledikten sonra lütfen sayfayı yenileyeniz. Daha sonrasında yeni ölçü bilgileriniz
                                ile tekrar sepete ekleyebilirsiniz.</span>

                                <div class="cursor-active">
                                    <img src="<?php bloginfo('template_url') ?>/img/icon/cursor.svg" alt="">
                                    <span>Kırpma özelliği aktif. Artık görsel üzerinde ölçülerinize en uygun alanı seçerek
                                    dilediğiniz gibi duvar kağıdını kesebilirsiniz.</span>
                                </div>
                                <div class="actions">
                                    <button class="ui-button-size--medium ui-button--primary fake-a-2" type="submit"
                                            name="crop">Ölçüyü Seç
                                    </button>
                                </div>
                            </div>
                            <div class="modalDetail mobile">
                                <div class="actions">
                                    <button class="ui-button-size--medium ui-button--primary fake-a-2" type="submit"
                                            name="crop">Ölçüyü Seç
                                    </button>
                                </div>
                                <span>Aynı üründen farklı ölçülerde satın alımlar gerçekleştirmek için ürününüzü sepete
                                    ekledikten sonra lütfen sayfayı yenileyeniz. Daha sonrasında yeni ölçü bilgileriniz
                                    ile tekrar sepete ekleyebilirsiniz.</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Material Modal -->
<div class="modal fade materialModal" id="materialModal" tabindex="-1" role="dialog"
     aria-labelledby="materialModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <img src="<?php bloginfo('template_url') ?>/img/icon/b-close.svg" alt="">
                </button>
            </div>
            <div class="modal-body">
                <h3>Doku Malzemesi Seçin</h3>
                <p>Kusursuz bir duvar kağıdı görünümü için dilediğiniz doku tipini seçin.</p>
                <div class="row">

                    <?php
                    for ($i = 0; $i < sizeof($materials); $i++) {
                        echo '<div class="col-md-3">
                        <div class="material-card">
                            <div class="material-image">
                                <div class="image-texture"></div>';
                        echo '<img src="' . wp_get_attachment_url($attachment_ids[0]) . '"
                                     class="zoom img-fluid"
                                     data-magnify-src="' . wp_get_attachment_url($attachment_ids[0]) . '"></div>';
                        echo '<ul>';
                        echo '<li>Birim: ' . $materials[$i]->price . get_woocommerce_currency_symbol().' / m² <br> Toplam: <span>' . (($current_width_cm * $current_height_cm) / 10000) * $materials[$i]->price . '</span></li>';
                        echo '<li>
                                    <button onclick="accordion' . ($i + 1) . '()">Ürün Özellikleri</button>
                                </li>';
                        echo '</ul>';
                        echo '<form method="post" action="">';
                        echo '<input name="height_cm" type="hidden" value="' . $current_height_cm . '">
                              <input name="width_cm" type="hidden" value="' . $current_width_cm . '">                           
                              <input type="hidden" name="crop_width" value="' . $current_width . '">
                              <input type="hidden" name="crop_height" value="' . $current_height . '">
                              <input type="hidden" name="crop_left" value="' . $current_pos_x . '">
                              <input type="hidden" name="crop_top" value="' . $current_pos_y . '">
                              <input type="hidden" name="color" class="color_crop" value="' . $current_style_index . '">';
                        echo '<p id="accordionText' . ($i + 1) . '">' . $materials[$i]->description . '</p>
                            <button class="ui-button--customize-outline ui-button-size--small"  name="material_choose" value="' . $i . '">';
                        echo $materials[$i]->name;
                        echo '</button>';
                        echo '</form>';
                        echo '</div></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Whatsapp -->
<a href="#" class="mobile-whatsapp">
    <img src="<?php bloginfo('template_url') ?>/img/icon/stick-whatsapp.svg" alt="">
</a>

<!-- jQuery Import -->
<script>
    $(document).ready(function () {
        $('.zoom').magnify();
    });
</script>
<script>
    jQuery(function ($) {
        $('#room_width, #room_length').keyup(function () {
            $(this).val($(this).val().replace(/\D+/, ''));
        });
    });
</script>
<script type="text/javascript">
    function calcRoom() {
        var room_width = $('#room_width').val() * 1; // edited
        var room_length = $('#room_length').val() * 1; // edited
        var room_wmeter = room_width / 100;
        var room_wlength = room_length / 100;
        var opt = $('.matmacc:checked').attr('price');
        var opt1 = $('.matmacc:checked').attr('maccprice');
        if (opt) {
            var totm = room_wmeter * room_wlength;
            var topp = parseFloat(totm) * parseFloat(opt);
            var topp1 = parseFloat(totm) * parseFloat(opt1);
            $('#custom_price').val(topp1);
            $('._SpecialProduct_qq24q').text(topp.toLocaleString('tr-TR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + 'TL');
        }
        $('carpet_width').innerHTML = room_width;
        $('carpet_length').innerHTML = room_length;
        $('#button-cart').tooltip('hide');
        $('#button-cart').removeAttr('disabled').removeAttr('data-original-title');
    }

    $('.matmacc').on('change', function () {
        var opt = $(this).attr('price');
        var opt1 = $(this).attr('maccprice');
        var room_width = $('#room_width').val() * 1; // edited
        var room_length = $('#room_length').val() * 1; // edited
        var room_wmeter = room_width / 100;
        var room_wlength = room_length / 100;

        if (opt) {
            var totm = room_wmeter * room_wlength;
            var topp = parseFloat(totm) * parseFloat(opt);
            var topp1 = parseFloat(totm) * parseFloat(opt1);
            $('#custom_price').val(topp1);
            $('._SpecialProduct_qq24q').text(topp.toLocaleString('tr-TR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + 'TL');
        }
    });

</script>
<script>
    function accordion1() {
        var x = document.getElementById("accordionText1");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }

    function accordion2() {
        var x = document.getElementById("accordionText2");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }

    function accordion3() {
        var x = document.getElementById("accordionText3");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }

    function accordion4() {
        var x = document.getElementById("accordionText4");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }
</script>


<?php get_footer() ?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js'></script>
<script>
    $(document).on("click", '[data-toggle="lightbox2"]', function (event) {
        event.preventDefault();
        $(this).ekkoLightbox({
            alwaysShowClose: true,
            showArrows: false
        });
    });
</script>