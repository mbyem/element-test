<?php

class ProductRest
{

    public static $restSettings;

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerRestRoutes']);
        self::$restSettings = [
            'namespace'    => 'products',
            'resourceName' => 'categories'
        ];

    }

    public function registerRestRoutes()
    {
        register_rest_route(self::$restSettings['namespace'],
            '/by-category-id/(?P<categoryId>[\d]+)', array(
                'methods'             => 'GET',
                'callback'            => array($this, 'getProductsByCategoryId'),
                'permission_callback' => '__return_true'
            )
        );

        register_rest_route(self::$restSettings['namespace'],
            '/by-category-name/(?P<categoryName>[\w\s\d\%]+)', array(
                'methods'             => 'GET',
                'callback'            => array($this, 'getProductsByCategoryName'),
                'permission_callback' => '__return_true'
            )
        );
    }

    /**
     * @param $request
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public function getProductsByCategoryId($request)
    {
        $categoryId = (int)$request['categoryId'];

        $category = get_term_by('id', $categoryId, 'product_category');
        if (!$category) {
            return rest_ensure_response(['error' => __('Category not found', TEXT_DOM)]);
        }

        $products = MProduct::getProductsByCategoryId($category->term_id);

        return $this->_prepareProducts($products);
    }

    /**
     * @param $request
     * @return WP_Error|WP_HTTP_Response|WP_REST_Response
     */
    public function getProductsByCategoryName($request)
    {
        $categoryName = trim( urldecode($request['categoryName'] ));

        //var_dump($categoryName);

        $category = get_term_by('name', $categoryName, 'product_category');
        if (!$category) {
            return rest_ensure_response(['error' => __('Category not found', TEXT_DOM)]);
        }
        $products = MProduct::getProductsByCategoryId($category->term_id);

        return $this->_prepareProducts($products);
    }

    private function _prepareProducts($products)
    {
        if (empty($products)) {
            return rest_ensure_response(['error' => __('No products found', TEXT_DOM)]);
        }

        $retArray = [];

        foreach ($products as $product) {
            $retArray[] = [
                'id'          => $product->ID,
                'title'       => $product->post_title,
                'description' => $product->post_content,
                'img_url'     => get_the_post_thumbnail_url($product),
                'url'         => get_permalink($product),
                'price'       => floatval($product->price),
                'is_on_sale'  => boolval($product->on_sale),
                'sale_price'  => floatval($product->sale_price)
            ];
        }

        return rest_ensure_response(['products' => $retArray]);

    }

}

add_action('init', function () {
    $productRest = new ProductRest();
});