<?php
/*
 * Template Name: My Promotions
 */
get_header();
if (!is_user_logged_in()) {
    header('Location:' . home_url() . '/giris-yap');
}
?>
<style>
    a:hover {
        color: inherit !important;
    }
</style>

<div class="container">
    <ul class="breadcrumb">
        <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
        <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
        <li><a class="disable" href="#">İndirim Kuponlarım</a></li>
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
                        $user_id = wp_get_current_user()->ID;
                        $meta = get_user_meta($user_id);

                        $first_name = $meta['first_name'][0];
                        $last_name = $meta['last_name'][0];
                        ?>
                        <h5><?php echo $first_name . " " . $last_name ?></h5>
                    </div>
                    <ul>
                        <li><a href="/siparislerim">Siparişlerim</a></li>
                        <li><a href="/adreslerim">Adreslerim</a></li>
                        <li><a href="/favorilerim">Favorilerim</a></li>
                        <li class="active"><a href="/promosyonlarim">İndirim Kuponlarım</a></li>
                        <li><a href="/hesabim">Kullanıcı Bilgilerim</a></li>
                        <li><a href="<?php echo wp_logout_url(home_url()) ?>">Çıkış Yap</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <div class="my-orders">
                    <h4>İndirim Kuponlarım</h4>
                    <table class="footable">
                        <tbody>
                        <?php
                        $args = array(
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'asc',
                            'post_type' => 'shop_coupon',
                            'post_status' => 'publish'
                        );

                        $coupons = get_posts($args);
                        $any_coupon = false;
                        foreach ($coupons as $coupon) {
                            if (!is_available($coupon)) {
                                continue;
                            }

                            $any_coupon = true;

                            $meta = get_post_meta($coupon->ID);
                            $coupon_category = unserialize($meta['product_categories'][0]);
                            if ($coupon_category) {
                                $category = get_term($coupon_category[0], 'product_cat');
                                $category_link = get_category_link($category);
                            } else {
                                $category = get_term_by('slug', '3d_duvar_posterleri', 'product_cat');
                                $category_link = get_category_link($category);
                            }
                            $title = $coupon->post_title;
                            $expires_date = $meta['date_expires'][0];
                            $coupon_amount = '%' . $meta['coupon_amount'][0] . ' İndirim';

                            if ($expires_date) {
                                $expires_date = wp_date('j F Y', $expires_date) . ' tarihine kadar geçerli';
                            } else {
                                $expires_date = "Zaman sınırı yok";
                            }

                            echo '<tr>
                                <td>
                                    <span>Promosyon Kodu: <b style="color:#000">' . $title . '</b></span>
                                <span>' . $coupon->post_excerpt . '</span>
                                </td>
                                <td>
                                    <span><b>' . $coupon_amount . '</b></span>
                                </td>';
                            echo '<td><a href="' . $category_link . '" class="noHover">Ürünleri Gör!</a></td>';

                            echo '<td style="text-align: right;">
                                    <span>' . $expires_date . '</span>
                                </td>
                            </tr>';
                        }

                        if (!$any_coupon) {
                            echo '<tr><td><span>Hesabınıza ait bir indirim kuponu bulunamadı.</span></td></tr>';
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer() ?>
<script src="<?php bloginfo('template_url') ?>/js/footable.min.js"></script>
<script>
    $('.footable').footable({
        breakpoints: {
            phone: 600,
            tablet: 1200
        }
    });
</script>
