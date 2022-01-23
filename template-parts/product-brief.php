<?php
/**
 * @var $product WP_Post
 */
if (!isset($args['product']) || !$args['product'] instanceof WP_Post) {
    $product = get_post();
} else {
    $product = $args['product'];
}
$href = esc_url(get_permalink($product));

$style = '';
if (isset($args['bg_color'])) {
    $style = 'style="background-color:' . $args['bg_color'] . '"';
}
?>
<div <?php post_class('prod-item gallery-item'); ?> <?php echo $style; ?> id="post-<?php the_ID(); ?>">
    <h3>
        <a href="<?php echo $href; ?>">
            <?php echo $product->post_title; ?>
        </a>
    </h3>
    <a href="<?php echo $href; ?>">
        <?php echo get_the_post_thumbnail($product, 'medium'); ?>
    </a>
    <div class="price_row">
        <a href="<?php echo $href; ?>">
            <strong>Price: </strong>
            <?php if ($product->on_sale == 1): ?>
                <span class="sale">Sale!</span>
                <span><s><?php echo $product->price; ?></s></span>
                &nbsp;&nbsp;
                <span><?php echo $product->sale_price; ?></span>
            <?php else: ?>
                <span><?php echo $product->price; ?></span>
            <?php endif; ?>
        </a>
    </div>


</div>

