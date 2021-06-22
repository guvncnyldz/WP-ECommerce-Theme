<?php get_header() ?>
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
                <div class="tab">
                    <?php
                    $categories = get_categories(array("taxonomy" => 'category', "hide_empty" => 0, "hierarchy" => 1));
                    $current_category = get_queried_object();
                    foreach ($categories as $category) {
                        if ($category->cat_ID == $current_category->term_id) {
                            echo '<a class="tablinks active" href="' . get_category_link($category->cat_ID) . '">' . $category->name . '</button>';
                        } else {
                            echo '<a class="tablinks" href="' . get_category_link($category->cat_ID) . '">' . $category->name . '</button>';
                        }
                    }
                    ?>
                </div>
                <!-- Tab content -->
                <div id="tumu" class="tabcontent" style="display: block;">
                    <div class="row">
                        <?php

                        $loop = get_posts_all('date','des',-1,$current_category->term_id);

                        $current_post = 0;
                        $right_posts = [];
                        $right_tags = [];

                        while ($loop->have_posts()) : $loop->the_post();
                            global $post;
                            $tags = get_the_tags($post->ID);

                            if ($current_post == 0) {
                                echo '<div class="col-md-8">';
                                Set_Post($post, $tags);
                            } else if ($current_post == 1) {
                                array_push($right_posts, $post);
                                array_push($right_tags, $tags);
                            } else {
                                if ($current_post % 3 != 1) {
                                    if ($current_post == 2) {
                                        echo '<div class="row">';
                                    }
                                    echo '<div class="col-md-6">';
                                    Set_Post($post, $tags);
                                    echo '</div>';
                                } else {
                                    array_push($right_posts, $post);
                                    array_push($right_tags, $tags);
                                }

                            }

                            $current_post++;
                        endwhile;

                        echo '</div>';
                        echo '</div>';

                        $sizeof_right = sizeof($right_posts);

                        if ($sizeof_right > 0) {
                            echo '<div class="col-md-4">';
                        }

                        for ($i = 0; $i < sizeof($right_posts); $i++) {
                            Set_Post($right_posts[$i], $right_tags[$i]);
                        }

                        if ($sizeof_right > 0) {
                            echo '</div>';
                        }

                        wp_reset_query();

                        function Set_Post($post, $tags)
                        {
                            echo '<div class="blog-post">';
                            echo '<a class="blog-post-a" href="' . get_the_permalink($post->ID) . '">';
                            echo '<div class="img-hover-zoom">';
                            echo '<img src="' . get_the_post_thumbnail_url($post->ID) . '" alt="">';
                            echo '</div>';
                            echo '</a>';
                            echo '<div class="content">';
                            echo '<span>' . wp_date('j F Y', strtotime($post->post_date))  . '</span>';
                            echo '<a class="blog-post-a" href="' . get_the_permalink($post->ID) . '">';
                            echo '<h4>' . $post->post_title;
                            echo '</a>';

                                    echo '<i>YENİ</i>';

                            echo ' </h4>';
                            echo '<p>' . $post->post_excerpt . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php get_footer(); ?>
