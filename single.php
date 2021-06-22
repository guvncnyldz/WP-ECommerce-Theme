<?php get_header(); ?>
</div>

<!-- Breadcrumb -->
<div class="container">
    <ul class="breadcrumb bottom-line">
        <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
        <?php
        global $post;
        $categories = [];
        $current_cat = get_the_category($post->ID);
        $current_cat = $current_cat[sizeof($current_cat) - 1];
        array_push($categories, $current_cat);

        $cat = $current_cat;
        while ($cat->parent) {
            $cat = get_term($cat->parent);

            if ($cat->parent)
                array_push($categories, $cat);
        }
        for ($i = sizeof($categories); $i >= 0; $i--) {
            echo '<li><a href="' . get_category_link($categories[$i]->term_id) . '">' . $categories[$i]->name . '</a></li>';

            if ($i != 0)
                echo '<li><img src="' . get_template_directory_uri() . '/img/icon/b-angle-small-right.svg" alt=""></li>';
        }
        echo '<li><img src="' . get_template_directory_uri() . '/img/icon/b-angle-small-right.svg" alt=""></li>';
        echo '<li><a class="disable">' . $post->post_name . '</a></li>';
        ?>
    </ul>
</div>

<!-- Blog Detial -->
<div class="container">
    <div class="blog-detail">
        <div class="padding">
            <div class="sectionTitle">
                <h6><?php echo $current_cat->name ?></h6>
                <h3><?php echo $post->post_title ?> </h3>
            </div>
        </div>
        <div class="padding">
            <p>
                <?php echo $post->post_content ?>
            </p>
        </div>
    </div>
    <div class="blog-detail-footer">
        <span><?php echo wp_date('j F Y', strtotime($post->post_date)) ?> tarihinde yayınlandı</span>
        <ul>
            <li>
                <a href="#">
                    <img src="dist/img/icon/footer-whatsapp.svg" alt="">
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="dist/img/icon/footer-facebook.svg" alt="">
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="dist/img/icon/footer-twitter.svg" alt="">
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Other Blogs -->
<div class="container">
    <div class="sectionHeader">
        <div class="sectionTitle">
            <h3>Diğer Yazılarımız</h3>
        </div>
    </div>
    <div class="row">
        <!-- Blog Post -->
        <?php
        $posts = get_posts_all('date', 'DESC', 3);

        while ($posts->have_posts()) {
            $posts->the_post();
            global $post;

            echo '<div class="col-md-4">
            <div class="product-view">';
            echo '<a href="'. get_the_permalink($post->ID).'" class="product">';
            echo '<img class="img-fluid" src="'.get_the_post_thumbnail_url($post->ID).'" alt="">';
            echo '<span>'.wp_date('j F Y', strtotime($post->post_date)).'</span>';
            echo '<h4>' . $post->post_title;
            echo '<p>'.$post->post_excerpt.'</p>';
            echo '</a>';
            echo '            </div>
        </div>';
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>
