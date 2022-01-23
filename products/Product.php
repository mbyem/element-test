<?php

require_once 'ProductShortcode.php';
require_once 'ProductRest.php';

class MProduct
{
    public function __construct()
    {
        // register CPT & Taxonomy
        $this->_registerCPT();
        $this->_registerTaxonomyCategory();

        $this->_registerMetaboxes();
    }

    private function _registerCPT()
    {
        $args = [
            'label'               => esc_html__('Products', TEXT_DOM),
            'labels'              => [
                'menu_name'          => esc_html__('Products', TEXT_DOM),
                'name_admin_bar'     => esc_html__('Product', TEXT_DOM),
                'add_new'            => esc_html__('Add Product', TEXT_DOM),
                'add_new_item'       => esc_html__('Add new Product', TEXT_DOM),
                'new_item'           => esc_html__('New Product', TEXT_DOM),
                'edit_item'          => esc_html__('Edit Product', TEXT_DOM),
                'view_item'          => esc_html__('View Product', TEXT_DOM),
                'update_item'        => esc_html__('View Product', TEXT_DOM),
                'all_items'          => esc_html__('All Products', TEXT_DOM),
                'search_items'       => esc_html__('Search Products', TEXT_DOM),
                'parent_item_colon'  => esc_html__('Parent Product', TEXT_DOM),
                'not_found'          => esc_html__('No Products found', TEXT_DOM),
                'not_found_in_trash' => esc_html__('No Products found in Trash', TEXT_DOM),
                'name'               => esc_html__('Products', TEXT_DOM),
                'singular_name'      => esc_html__('Product', TEXT_DOM),
            ],
            'public'              => TRUE,
            'exclude_from_search' => FALSE,
            'publicly_queryable'  => TRUE,
            'show_ui'             => TRUE,
            'show_in_nav_menus'   => TRUE,
            'show_in_admin_bar'   => FALSE,
            'show_in_rest'        => TRUE,
            'capability_type'     => 'post',
            'hierarchical'        => FALSE,
            'has_archive'         => TRUE,
            'query_var'           => TRUE,
            'can_export'          => TRUE,
            'rewrite_no_front'    => FALSE,
            'show_in_menu'        => TRUE,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-list-view',
            'supports'            => [
                'title',
                'editor',
                'thumbnail',
                //'custom-fields', // comment to remove from classic editor view
            ],
            'taxonomies'          => [
                'product_category',
            ],
            'rewrite'             => TRUE
        ];

        register_post_type('product', $args);

    }

    private function _registerTaxonomyCategory()
    {
        $args = [
            'label'                => esc_html__('Product Categories', TEXT_DOM),
            'labels'               => [
                'menu_name'                  => esc_html__('Product Categories', TEXT_DOM),
                'all_items'                  => esc_html__('All Product Categories', TEXT_DOM),
                'edit_item'                  => esc_html__('Edit Product Category', TEXT_DOM),
                'view_item'                  => esc_html__('View Product Category', TEXT_DOM),
                'update_item'                => esc_html__('Update Product Category', TEXT_DOM),
                'add_new_item'               => esc_html__('Add new Product Category', TEXT_DOM),
                'new_item'                   => esc_html__('New Product Category', TEXT_DOM),
                'parent_item'                => esc_html__('Parent Product Category', TEXT_DOM),
                'parent_item_colon'          => esc_html__('Parent Product Category', TEXT_DOM),
                'search_items'               => esc_html__('Search Product Categories', TEXT_DOM),
                'popular_items'              => esc_html__('Popular Product Categories', TEXT_DOM),
                'separate_items_with_commas' => esc_html__('Separate Product Categories with commas', TEXT_DOM),
                'add_or_remove_items'        => esc_html__('Add or remove Product Categories', TEXT_DOM),
                'choose_from_most_used'      => esc_html__('Choose most used Product Categories', TEXT_DOM),
                'not_found'                  => esc_html__('No Product Categories found', TEXT_DOM),
                'name'                       => esc_html__('Product Categories', TEXT_DOM),
                'singular_name'              => esc_html__('Product Category', TEXT_DOM),
            ],
            'public'               => TRUE,
            'show_ui'              => TRUE,
            'show_in_menu'         => TRUE,
            'show_in_nav_menus'    => TRUE,
            'show_tagcloud'        => TRUE,
            'show_in_quick_edit'   => TRUE,
            'show_admin_column'    => TRUE,
            'show_in_rest'         => TRUE,
            'hierarchical'         => TRUE,
            'query_var'            => TRUE,
            'sort'                 => FALSE,
            'rewrite_no_front'     => FALSE,
            'rewrite_hierarchical' => FALSE,
            'rewrite'              => TRUE
        ];
        register_taxonomy('product_category', ['product'], $args);
    }

    private function _registerMetaboxes() {
        add_action('add_meta_boxes',[$this,'_registerMetaboxesRender'], 30);
        add_action('edit_post_product',[$this,'saveMetaboxesData'], 30);

    }

    public function _registerMetaboxesRender()
    {
        add_meta_box('product_price', __('Price', TEXT_DOM),
            [$this, 'renderMetaboxPrice'], 'product', 'normal');
        add_meta_box('product_on_sale', __('Is on sale?', TEXT_DOM),
            [$this, 'renderMetaboxOnSale'], 'product', 'normal');
        add_meta_box('product_sale_price', __('Sale price', TEXT_DOM),
            [$this, 'renderMetaboxSalePrice'], 'product', 'normal');
        add_meta_box('product_gallery', __('Gallery', TEXT_DOM),
            [$this, 'renderMetaboxGallery'], 'product', 'normal');
        add_meta_box('product_youtube', __('Youtube code', TEXT_DOM),
            [$this, 'renderMetaboxYoutube'], 'product', 'normal');


    }

    public function renderMetaboxPrice($product)
    {

        $price = get_post_meta($product->ID, 'price', TRUE);

        $nonceKey = 'ele_test_key';
        wp_nonce_field($nonceKey, $nonceKey, FALSE);

        ?>
        <p>
        <label for="price"><?php _e('Price', TEXT_DOM); ?></label>
        <input type="text" id="price" name="price" value="<?php echo floatval($price); ?>" class="widefat"/>
        </p><?php

    }

    public function renderMetaboxSalePrice($product)
    {
        $sale_price = get_post_meta($product->ID, 'sale_price', TRUE);

        ?>
        <p>
        <label for="sale_price"><?php _e('Sale price', TEXT_DOM); ?></label>
        <input type="text" id="sale_price" name="sale_price" value="<?php echo floatval($sale_price); ?>"
               class="widefat"/>
        </p><?php

    }

    public function renderMetaboxOnSale($product)
    {
        $on_sale = get_post_meta($product->ID, 'on_sale', TRUE);

        ?>
        <p>
        <label for="on_sale"><?php _e('On sale', TEXT_DOM); ?></label>

        <input type="checkbox" id="on_sale" name="on_sale"
               value="1" <?php echo( $on_sale == '1' ? 'checked="checked"' : '' ); ?> class="widefat"/>
        </p><?php

    }

    public function renderMetaboxYoutube($product)
    {
        $youtube_code = get_post_meta($product->ID, 'youtube_code', TRUE);

        ?>
        <p>
        <label for="youtube_code"><?php _e('Youtube code', TEXT_DOM); ?></label>
        <input type="text" id="youtube_code" name="youtube_code" value="<?php echo trim($youtube_code); ?>"
               class="widefat"/>
        </p><?php

    }

    public function renderMetaboxGallery($product)
    {
        $post_ID = $product->ID;
        printf( "<iframe frameborder='0' src=' %s ' style='width: 100%%; height: 400px;'> </iframe>", get_upload_iframe_src('media', $post_ID) );
    }

    public function saveMetaboxesData($post_id)
    {
        $nonceKey = 'ele_test_key';
        if (!isset($_POST[$nonceKey]) || !wp_verify_nonce($_POST[$nonceKey], $nonceKey)) {
            return;
        }

        $price = floatval($_POST['price']);
        $sale_price = floatval($_POST['sale_price']);
        $on_sale = intval($_POST['on_sale']);
        $youtube_code = trim($_POST['youtube_code']);

        if (!empty($price)) {
            update_post_meta($post_id, 'price', $price);
        }
        if (!empty($sale_price)) {
            update_post_meta($post_id, 'sale_price', $sale_price);
        }

        if($on_sale!==1) {
            $on_sale = 0;
        }
        update_post_meta($post_id, 'on_sale', $on_sale);

        if (!empty($youtube_code)) {
            update_post_meta($post_id, 'youtube_code', $youtube_code);
        }
    }

    public static function getProductsByCategoryId($id)
    {
        return get_posts(
            [
                'posts_per_page' => -1,
                'post_type'      => 'product',
                'tax_query'      => [
                    [
                        'taxonomy' => 'product_category',
                        'field'    => 'term_id',
                        'terms'    => (int)$id
                    ]
                ]
            ]);
    }

}

add_action('init', function () {
    $product = new MProduct();
});

