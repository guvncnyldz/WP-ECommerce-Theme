<div id="footer" class="container-fluid">
    <div class="shopping-band">
        <div class="row">
            <div class="col-md-3">
                <div class="box">
                    <img src="<? bloginfo('template_url'); ?>/img//icon/footer-security.svg" alt="">
                    <div class="text">
                        <h6>GÜVENLİ ALIŞVERİŞ</h6>
                        <span>Lorem ipsum dolor sit amet, consectetur</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <img src="<? bloginfo('template_url'); ?>/img/icon/footer-cargo.svg" alt="">
                    <div class="text">
                        <h6>HIZLI VE GÜVENLİ KARGO</h6>
                        <span>Adipiscing elit. Nam malesuada turpis.</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <img src="<? bloginfo('template_url'); ?>/img//icon/footer-customer.svg" alt="">
                    <div class="text">
                        <h6>MÜŞTERİ MEMNUNİYETİ</h6>
                        <span>Mauris tempor mauris nisi Praesent.</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box no-border">
                    <img src="<? bloginfo('template_url'); ?>/img//icon/footer-support.svg" alt="">
                    <div class="text">
                        <h6>7 / 24 DESTEK</h6>
                        <span>Lorem ipsum dolor sit amet elit.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <style>
            .tnp-email {
                border: none !important;
                border-bottom: 1px solid #e1e2e3 !important;
                background: transparent !important;
            }
            .tnp-submit {
                background: transparent !important;
                color: black !important;
                font-weight: 450 !important;
                border-bottom: 1px solid #e1e2e3 !important;
            }
        </style>
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <a href=<?php echo home_url(); ?>>
                    <?php $logo = get_field('logo', get_option('page_on_front')); ?>
                    <img class="logo" src="<?php echo $logo['siyah_logo'] ?>" alt="Logo">
                    </a>
                </div>
                <div class="col-md-3">
                    <ul class="menu">
                        <li class="title">KURUMSAL</li>
                        <?php
                        $blog = false;
                        $pages = get_pages_all('menu_order', 'ASC', 10);
                        while ($pages->have_posts()) {
                            $pages->the_post();
                            global $post;
                            echo '<li><a target="_blank" href="' . get_page_link($pages->id) . '">' . $post->post_title . '</a></li>';

                            if (!$blog) {
                                $blog = true;
                                $categories = get_categories();

                                echo '<li><a target="_blank" href="' . get_category_link($categories[0]->term_id) . '">Blog</a></li>';
                            }
                        }
                        ?>

                    </ul>
                </div>
                <div class="col-md-3">
                    <ul class="menu">
                        <li class="title">MENÜ</li>
                        <?php
                        $categories = get_categories_hierarchically('product_cat');

                        foreach ($categories->subcategories as $cat) {
                            echo '<li><a href="' . get_category_link($cat->cat->term_id) . '">' . $cat->cat->name . '</a></li>';
                        }
                        ?>

                    </ul>
                </div>
                <div class="col-md-4">
                    <form>
                        <h5>ABONE OLUN</h5>
                        <p>Özel içerik, teklifler ve indirimler almak için
                            haber bültenine abone olun!</p>
                    </form>
                    <style>
                        .tnp-email::placeholder{
                            font-family: var(--ff-jost) !important;
                            font-size: var(--fs-14) !important;
                            line-height: var(--lh-14) !important;
                            font-weight: var(--fw-regular) !important;
                        }
                        .tnp-submit {
                            font-family: var(--ff-jost) !important;
                            font-size: var(--fs-14) !important;
                            line-height: var(--lh-14) !important;
                            font-weight: var(--fw-regular) !important;
                            padding: 7px !important;
                        }
                    </style>
                    <?php echo do_shortcode('[newsletter_form type="minimal" lists="1"]') ?>


                    <!--<div class="input-group">
                        <input type="mail" placeholder="E-posta adresiniz">
                        <button>Gönder</button>
                    </div>-->
                    <div class="social-media">
                        <ul>
                            <?php
                            $sosyal_medya = get_field('sosyal_medya', get_option('page_on_front'));
                            $instagram = $sosyal_medya['instagram'];
                            $twitter = $sosyal_medya['twitter'];
                            $pinterest = $sosyal_medya['pinterest'];
                            $facebook = $sosyal_medya['facebook'];
                            $youtube = $sosyal_medya['youtube'];

                            if ($instagram || $pinterest || $facebook || $twitter)
                                echo '<h5 > SOSYAL MEDYA </h5 >';

                            if ($instagram) {
                                echo ' <li>
                                <a href="' . $instagram . '" target="_blank">
                                    <img src="' . get_template_directory_uri() . '/img//icon/footer-instagram.svg" alt="">
                                </a>
                            </li>';
                            }
                            if ($pinterest) {
                                echo ' <li>
                                <a href="' . $pinterest . '" target="_blank">
                                    <img src="' . get_template_directory_uri() . '/img//icon/footer-pinterest.svg" alt="">
                                </a>
                            </li>';
                            }
                            if ($facebook) {
                                echo ' <li>
                                <a href="' . $facebook . '" target="_blank">
                                    <img src="' . get_template_directory_uri() . '/img//icon/footer-facebook.svg" alt="">
                                </a>
                            </li>';
                            }
                            if ($twitter) {
                                echo ' <li>
                                <a href="' . $twitter . '" target="_blank">
                                    <img src="' . get_template_directory_uri() . '/img//icon/footer-twitter.svg" alt="">
                                </a>
                            </li>';
                            }
                            if ($youtube) {
                                echo ' <li>
                                <a href="' . $youtube . '" target="_blank">
                                    <img src="' . get_template_directory_uri() . '/img//icon/footer-youtube.svg" alt="">
                                </a>
                            </li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bottom -->
        <div class="container">
            <div class="logos">
                <img class="bank" src="<? bloginfo('template_url'); ?>/img//bankLogos.png" alt="">
                <img class="secure" src="<? bloginfo('template_url'); ?>/img//secureLogos.png" alt="">
            </div>
            <div class="copyright">
                <p>Duvar Kağıdı Marketi © Copyright <?php echo date("Y"); ?> Duvar Kağıdı Marketi Tic. A.Ş. Her Hakkı Saklıdır.</p>
                <span>
                        <i>Bu web sitesi</i>
                        <a target="_blank" href="http://peakbeck.com/"><img
                                    src="<? bloginfo('template_url'); ?>/img//peakbeckLogo.svg" alt=""></a>
                        <i>tarafından hazırlanmıştır.</i>
                    </span>
            </div>
        </div>

    </div>


</div>
<!-- jQuery Import -->

<?php wp_footer(); ?>
<script type="text/javascript">
    $(document).on("ready", function () {
        $(".main-slider").slick({
            infinite: true,
            dots: true,
            arrows: false,
            speed: 300,
            slidesToShow: 1,
            adaptiveHeight: true,
        });
        $(".categorySlide").slick({
            nextArrow: ".next-arrow",
            prevArrow: ".prev-arrow",
            infinite: false,
            slidesToScroll: 1,
            slidesToShow: 3.3,
            accessibility: true,
            variableWidth: false,
            focusOnSelect: false,
            centerMode: false,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToScroll: 2,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToScroll: 1,
                        slidesToShow: 1
                    }
                }
            ]
        });
    });
    $(".masonry-grid").masonry({
        itemSelector: ".masonry-grid-item",
        columnWidth: 280,
        gutter: 24,
    });
</script>
</body>
</html>


