<?php
/**
 * Add the field to the checkout page
 */
global $woocommerce;
?>

<?php get_header() ?>
<div class="container">
    <div class="payment-detail">
<?php echo do_shortcode('[woocommerce_checkout]')?>
    </div>
</div>
<?php get_footer() ?>