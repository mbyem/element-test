<?php

/**
 * Shortcode class
 */
class ProductShortcode
{
    public function __construct()
    {
        add_filter('product_short_filter', [$this, 'exampleFilter'], 10,2);
        add_shortcode('product_short', [$this, 'getShortcode']);


    }

    public function getShortcode($iAtts)
    {
        $parsedAtts = shortcode_atts(['product_id' => '', 'bg_color' => ''], $iAtts);

        return $this->prepareShortcode($parsedAtts['product_id'], $parsedAtts['bg_color']);
    }

    public function prepareShortcode($product_id, $bg_color)
    {
        if (empty($product_id)) {
            $product_id = null;
        }
        $product = get_post($product_id);

        if(empty($product)){
            return ;
        }

        ob_start();
        get_template_part('template-parts/product-brief', 'product',
            ['product'  => $product,
             'bg_color' => $bg_color]
        );
        $ret_str = ob_get_clean();

        $ret_str = apply_filters('product_short_filter', $ret_str);

        return $ret_str;
    }

    public function exampleFilter($text)
    {
        return str_replace('Price:', 'Payment:', $text);
    }
}

add_action('init', function () {
    $productShortcode = new ProductShortcode();
});