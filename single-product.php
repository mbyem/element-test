<?php
get_header();

/**
 * @var $product WP_Post
 */
$product = get_post();

$main_product_id = $product->ID;

$taxonomy = get_the_terms($product, 'product_category');

if (!empty($taxonomy)) {
    $taxonomy = $taxonomy[0];
}

$related_products_array = MProduct::getProductsByCategoryId($taxonomy->term_id);

?>
<main id="site-content" role="main">
    <div class="main-container">
        <div class="product-page">
            <div class="block-25">
                <?php echo get_the_post_thumbnail($product, 'medium'); ?>
            </div>
            <div class="block-75">
                <h1>
                    <?php echo $product->post_title; ?>
                </h1>
                <p>
                    <?php echo $product->post_content; ?>
                </p>
                <?php if (!empty($product->youtube_code)): ?>
                    <p class="youtube_block">
                        <iframe src="https://www.youtube.com/embed/<?php echo $product->youtube_code; ?>"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </p>
                <?php endif; ?>
                <div class="prods-gallery-inner">
                    <h3>Gallery:</h3>
                    <?php echo gallery_shortcode(
                        [
                            'id'      => $product->ID,
                            'columns' => 6,
                            'size'    => [150, 150]
                        ]); ?>
                </div>
                <div class="price_row">
                    <strong>Price:</strong>

                    <?php if ($product->on_sale == 1): ?>
                        <span class="sale">Sale!</span>
                        <span><s><?php echo $product->price; ?></s></span>
                        &nbsp;&nbsp;
                        <span><?php echo $product->sale_price; ?></span>
                    <?php else: ?>
                        <span><?php echo $product->price; ?></span>
                    <?php endif; ?>

                </div>
            </div>

        </div>
        <?php
        wp_reset_postdata();
        ?>

        <?php if (!empty($related_products_array) && count($related_products_array) > 1): ?>
            <h3>Related products:</h3>
            <div class="prods-gallery">

                <?php foreach ($related_products_array as $product): ?>
                    <?php
                    // skip current product
                    if ($product->ID === $main_product_id) {
                        continue;
                    }
                    get_template_part('template-parts/product-brief', 'product', ['product' => $product]);
                    ?>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>
</main>


<?php
get_footer();
?>

