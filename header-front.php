<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duvar Kağıdı Marketi</title>

    <?php wp_head(); ?>
    <style>
        a {
            color: inherit;
            text-decoration: none;
        }
    </style>
</head>
<body>

<style>
    .dgwt-wcas-preloader {
        right: 30px !important;
    }

    .navbar-light .dgwt-wcas-ico-magnifier {
        z-index: 1;
        right: 12px !important;
        left: auto !important;
        height: 40% !important;
    }

    .navbar-light input[type=search].dgwt-wcas-search-input {
        background: #F2F2F2 !important;
        border: 0 !important;
        color: #686868 !important;
        padding: 25px 18px !important;
    }

    .navbar-light input[type=search].dgwt-wcas-search-input::placeholder {
        font-family: var(--ff-jost) !important;
        font-weight: var(--fw-regular) !important;
        font-style: normal !important;
        color: black !important;
        opacity: 1 !important;
    }

    .navbar-dark .dgwt-wcas-ico-magnifier {
        z-index: 1;
        right: 12px !important;
        left: auto !important;
        height: 40% !important;
        filter: invert(96%) sepia(84%) saturate(0%) hue-rotate(129deg) brightness(104%) contrast(107%);
    }

    .navbar-dark input[type=search].dgwt-wcas-search-input {
        background: rgba(255, 255, 255, .12) !important;
        backdrop-filter: blur(16px) !important;
        border: 1px solid rgba(255, 255, 255, 0.18) !important;
        color: #FFFFFF !important;
        padding: 25px 18px !important;
    }

    .navbar-dark input[type=search].dgwt-wcas-search-input::placeholder {
        font-family: var(--ff-jost) !important;
        font-weight: var(--fw-regular) !important;
        font-style: normal !important;
        color: rgba(255, 255, 255, 0.6) !important;
        opacity: 1 !important;
    }

    .navbar-dark input[type=search].dgwt-wcas-search-input:focus {
        background: rgba(255, 255, 255, 0.4) !important;
        color: #2F2F2F !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
    }
</style>
<!-- Hero -->
<div class="hero">

    <!-- White Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light none-view">
        <div class="container">
            <!-- Logos -->
            <a class="navbar-brand" href=<?php echo home_url(); ?>>
                <?php $logo = get_field('logo', get_option('page_on_front')); ?>
                <img class="default-logo" src="<? echo $logo['beyaz_logo'] ?>" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    $categories = get_categories_hierarchically('product_cat');
                    foreach ($categories->subcategories as $cat) {
                        echo '<li class="nav-item dropdown">';

                        if ($cat->subcategories) {
                            echo '<a class="nav-link dropdown-toggle" href="' . get_category_link($cat->cat->cat_ID) . '" id="navbarDropdown"role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                        } else
                            echo '<a class="nav-link nav-discounted" href="' . get_category_link($cat->cat->cat_ID) . '" id="navbarDropdown"role="button" data-bs-toggle="dropdown" aria-expanded="false">';

                        echo tr_strtoupper($cat->cat->name);
                        echo '</a>';

                        if ($cat->subcategories) {
                            echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                            foreach ($cat->subcategories as $sub_cat) {
                                echo '<li>';
                                echo '<a class="dropdown-item" href="' . get_category_link($sub_cat->cat->cat_ID) . '">';
                                echo $sub_cat->cat->name;
                                echo '<img src="' . get_template_directory_uri() . '/img/icon/b-link-arrow.svg" alt="">';
                                echo '</a>';
                                echo '</li>';
                            }

                            echo '</ul>';
                        }
                        echo '</li>';

                    }
                    ?>
                </ul>
                <?php echo do_shortcode('[fibosearch]') ?>
                <!--<form class="d-flex search-from">
                    <input class="form-control search-input navbar-gray-search me-2" placeholder="Arama"
                           aria-label="Search">
                    <img class="light-search" src="<? /*bloginfo('template_url');*/ ?>/img/icon/b-search.svg" alt="">
                </form>-->
                <ul class="d-flex user-menu">
                    <li class="nav-item-icon none-padding dropdown">
                        <a href="/siparislerim" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <img src="<? bloginfo('template_url'); ?>/img/icon/b-user-profile.svg" alt="">
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php if (is_user_logged_in()) : ?>
                                <li>
                                    <a class="dropdown-item" href="/siparislerim">
                                        Siparişlerim
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/promosyonlarim">
                                        İndirim Kuponlarım
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/adreslerim">
                                        Adreslerim
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/hesabim">
                                        Kullanıcı Bilgilerim
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo wp_logout_url(home_url()) ?>">
                                        Çıkış Yap
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                            <?php else : ?>
                                <li>
                                    <a class="dropdown-item" href="/giris-yap">
                                        Üye Girişi
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/kayit-ol">
                                        Üye Ol
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="/favorilerim">
                            <img src="<? bloginfo('template_url'); ?>/img/icon/b-favorite.svg" alt="">
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo apply_filters('woocommerce_get_cart_url', wc_get_page_permalink('cart')) ?>">
                            <?php
                            global $woocommerce;
                            $items = $woocommerce->cart->get_cart();
                            $size = sizeof($items);

                            if ($size > 0) {
                                echo '<div class="shopping-circle">' . $size . '</div>';
                            }
                            ?>
                            <img src="<? bloginfo('template_url'); ?>/img/icon/b-shopping.svg" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <!-- Logos -->
            <a class="navbar-brand" href=<?php echo home_url(); ?>>
                <?php $logo = get_field('logo', get_option('page_on_front')); ?>
                <img class="default-logo" src="<? echo $logo['beyaz_logo'] ?>" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContentTransparent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Menu -->
            <div class="collapse navbar-collapse" id="navbarSupportedContentTransparent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <?php
                    $categories = get_categories_hierarchically('product_cat');
                    foreach ($categories->subcategories as $cat) {
                        echo '<li class="nav-item dropdown">';

                        if ($cat->subcategories) {
                            echo '<a class="nav-link dropdown-toggle" href="' . get_category_link($cat->cat->cat_ID) . '" id="navbarDropdown"role="button" data-bs-toggle="dropdown" aria-expanded="false">';
                        } else
                            echo '<a class="nav-link nav-discounted" href="' . get_category_link($cat->cat->cat_ID) . '" id="navbarDropdown"role="button" data-bs-toggle="dropdown" aria-expanded="false">';

                        echo tr_strtoupper($cat->cat->name);
                        echo '</a>';

                        if ($cat->subcategories) {
                            echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                            foreach ($cat->subcategories as $sub_cat) {
                                echo '<li>';
                                echo '<a class="dropdown-item" href="' . get_category_link($sub_cat->cat->cat_ID) . '">';
                                echo $sub_cat->cat->name;
                                echo '<img src="' . get_template_directory_uri() . '/img/icon/b-link-arrow.svg" alt="">';
                                echo '</a>';
                                echo '</li>';
                            }

                            echo '</ul>';
                        }
                        echo '</li>';

                    }
                    ?>
                </ul>
                <?php echo do_shortcode('[fibosearch]') ?>

                <!--<form class="d-flex search-from">
                    <input class="form-control search-input navbar-blur-search me-2" placeholder="Arama"
                           aria-label="Search">
                    <img class="light-search" src="<? /* bloginfo('template_url'); */ ?>/img/icon/w-search.svg" alt="">
                </form>-->
                <ul class="d-flex user-menu">
                    <li class="nav-item-icon none-padding dropdown">
                        <a href="/siparislerim" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <img src="<? bloginfo('template_url'); ?>/img/icon/w-user-profile.svg" alt="">
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php if (is_user_logged_in()) : ?>
                                <li>
                                    <a class="dropdown-item" href="/siparislerim">
                                        Siparişlerim
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/promosyonlarim">
                                        İndirim Kuponlarım
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/adreslerim">
                                        Adreslerim
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/hesabim">
                                        Kullanıcı Bilgilerim
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo wp_logout_url(home_url()) ?>">
                                        Çıkış Yap
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                            <?php else : ?>
                                <li>
                                    <a class="dropdown-item" href="/giris-yap">
                                        Üye Girişi
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/kayit-ol">
                                        Üye Ol
                                        <img src="<? bloginfo('template_url'); ?>/img/icon/b-link-arrow.svg" alt="">
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="/favorilerim">
                            <img src="<? bloginfo('template_url'); ?>/img/icon/w-favorite.svg" alt="">
                        </a>
                    </li>
                    <li>

                        <a href="<?php echo apply_filters('woocommerce_get_cart_url', wc_get_page_permalink('cart')) ?>">
                            <?php
                            global $woocommerce;
                            $items = $woocommerce->cart->get_cart();
                            $size = sizeof($items);

                            if ($size > 0) {
                                echo '<div class="shopping-circle">' . $size . '</div>';
                            }
                            ?>
                            <img src="<? bloginfo('template_url'); ?>/img/icon/w-shopping.svg" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="main-slider slider">
        <!--    --><?php
        $category_banner = get_field('kategori_slider');
        $categories = get_all_childless('product_cat', '', 'count', 'DESC', 6);

        $i = 0;
        while (true) {
            $field = $category_banner['alan_' . ($i + 1)];
            if ($field == null)
                break;

            if ($field['aktif'] == 'false') {
                $i++;
                continue;
            }
            if ($field['otomatik'] == 'true') {
                $cat = $categories[$i]->cat;
                $thumbnail_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
                $image = wp_get_attachment_url($thumbnail_id);
                echo '<div class="slide" style="background: url(' . $image . ')no-repeat center; background-size: cover;">';
                echo '<div class="container">';
                echo '<div class="content">';
                echo '<h1>' . $cat->name . '</h1>';
                echo '<p>' . $cat->description . '</p>';
                echo '<a class="ui-button--thirdy ui-button-size--large" href="' . get_category_link($cat->term_id) . '">Tüm Ürünler</a>';
                echo '</div>';
                echo '</div>';
                echo '<div class="slide-backdrop"></div>';
                echo '</div>';
            } else {
                echo '<div class="slide" style="background: url(' . $field['foto'] . ')no-repeat center; background-size: cover;">';
                echo '<div class="container">';
                echo '<div class="content">';
                echo '<h1>' . $field['baslik'] . '</h1>';
                echo '<p>' . $field['aciklama'] . '</p>';
                echo '<a class="ui-button--thirdy ui-button-size--large" href="' . $field['kategori_linki'] . '">Tüm Ürünler</a>';
                echo '</div>';
                echo '</div>';
                echo '<div class="slide-backdrop"></div>';
                echo '</div>';
            }
            $i++;
        }
        ?>
    </section>
</div>

<!-- Category Menu Fixed  -->
<script>
    var myInterval = setInterval(function () {
        if ($(".categoryMenuClose")[0]) {

            $('.title-white').fadeOut(600, function () {
                $(this).delay(500).fadeIn(600);
            })
            $('.white-left').fadeOut(600, function () {
                $(this).delay(500).fadeIn(600);
            })
        }

    }, 4000);
</script>
<!-- Category Menu Fixed  -->
<div class="categoryMenu categoryMenuClose" style="">
    <button class="categoryMenuBtn left" style="position: relative;">
        <img class="angleButton" style="position: absolute; top: 12px; left: 12px"
             src="<? bloginfo('template_url'); ?>/img/icon/w-angle-double-yellow.svg" alt="">
        <img class="angleButton white-left" style="position: absolute; top: 12px; left: 12px"
             src="<? bloginfo('template_url'); ?>/img/icon/w-angle-double.svg" alt="">
    </button>
    <div class="title" style="position: relative">
        <img class="img-fluid title-yellow" style="position: absolute; top: 0; left: 0"
             src="<? bloginfo('template_url'); ?>/img/menuTitle-yellow.png" alt="">
        <img class="img-fluid title-white" style="position: absolute; top: 0; left: 0"
             src="<? bloginfo('template_url'); ?>/img/menuTitle.png" alt="">
    </div>
    <ul>
        <?php
        $categories = get_subcategories(get_term_by('slug', '3d_duvar_posterleri', 'product_cat')->term_id, 'product_cat', 'name', 'ASC');
        foreach ($categories as $category) {
            echo '<li>';
            echo '<a href = "' . get_category_link($category->cat->cat_ID) . '">';
            echo '<span>' . $category->cat->name . '</span>';
            echo '<img src=' . get_template_directory_uri() . '/img/icon/w-link-arrow.svg" alt="">';
            echo '</a>';
            echo '</li>';
        } ?>
    </ul>
</div>

