<?php get_header();

$post = get_queried_object();
$pagename = $post->post_title;
?>
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="<?php echo home_url(); ?>">Ana Sayfa</a></li>
            <li><img src="<?php bloginfo(template_url) ?>/img/icon/b-angle-small-right.svg" alt=""></li>
            <li><a class="disable" href="#"><?php echo $pagename?></a></li>
        </ul>
    </div>
<?php
echo the_content();
?>
<?php get_footer() ?>