<?php get_header();

$post = get_queried_object();
$pagename = $post->post_title;

preg_match_all('/(?<=\[).+?(?=])/i', $post->post_content, $output);

$sponsor_photo = [];
$sponsor_desc = [];
for ($i = 0; $i < sizeof($output[0]); $i++) {
    if ($i % 2 == 0) {
        array_push($sponsor_photo, $output[0][$i]);
    } else {
        array_push($sponsor_desc, $output[0][$i]);
    }
}

console_log($sponsor_photo);
console_log($sponsor_desc);
?>
    <style>
        .sectionTitle {
            margin-top: 0 !important;
        }
    </style>
    <div class="container-fluid">
        <div class="blog">
            <div class="container">
                <div class="sectionTitle">
                    <h6>SPONSOR</h6>
                    <h3>Desteklediğimiz Markalar</h3>
                </div>
                <!-- Tab content -->
                <div id="tumu" class="tabcontent" style="display: block;">
                    <div class="row">
                        <div class="row">
                            <?php

                            $current_post = 0;
                            $total_post = sizeof($sponsor_photo);
                            $right_posts = [];

                            for($current_post=0;$current_post<$total_post;$current_post++) {

                                if ($current_post == 0) {
                                    echo '<div class="col-md-8">';
                                    Set_Post($current_post, $sponsor_photo, $sponsor_desc);
                                } else if ($current_post == 1) {
                                    array_push($right_posts, $current_post);
                                } else {
                                    if ($current_post % 3 != 1) {
                                        if ($current_post == 2) {
                                            echo '<div class="row">';
                                        }
                                        echo '<div class="col-md-6">';
                                        Set_Post($current_post, $sponsor_photo, $sponsor_desc);
                                        echo '</div>';
                                    } else {
                                        array_push($right_posts, $current_post);
                                    }

                                }
                            }

                            echo '</div>';
                            echo '</div>';

                            $sizeof_right = sizeof($right_posts);

                            if ($sizeof_right > 0) {
                                echo '<div class="col-md-4">';
                            }

                            for ($i = 0; $i < sizeof($right_posts); $i++) {
                                Set_Post($right_posts[$i],$sponsor_photo,$sponsor_desc);
                            }

                            if ($sizeof_right > 0) {
                                echo '</div>';
                            }

                            wp_reset_query();

                            function Set_Post($i,$sponsor_photo,$sponsor_desc)
                            {
                                echo '<div class="blog-post">';
                                echo '<div class="img-hover-zoom">';
                                echo  $sponsor_photo[$i];
                                echo '</div>';
                                echo '<div class="content">';
                                echo '<h4>' . $sponsor_desc[$i];
                                echo ' </h4>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer() ?>