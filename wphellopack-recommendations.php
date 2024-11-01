<?php
/*
Plugin Name: WPhellopack Recommendations for WooCommerce
Plugin URI: https://wphellopack.com/product/wphellopack-recommendations-for-woocommerce
Description: WPhellopack Recommendations for WooCommerce is a plugin serving products basing on the customer's journey.
Author: Andrzej Bernat
Version: 1.0.0
Author URI: https://wphellopack.com
 */

// Include the Recommender class.
if (!class_exists('Recommender', false)) {
    include_once dirname(__FILE__) . '/includes/class-recommender.php';
}

/**
 * Attaches recommendation JS engine
 *
 * @return void
 */
function wphellopack_recommendations_js_init()
{
    wp_enqueue_script('wphellopack-recommendations-recommender-js', plugins_url('/assets/js/recommender.js', __FILE__));
}

/**
 * Saves user's history
 *
 * @return void
 */
function wphellopack_track_user_steps()
{

    global $product;

    // Start the script tag
    echo "<script type='text/javascript'>\n";

    // Set viewed categories
    echo "document.addEventListener('DOMContentLoaded', function () {\n";
    foreach ($product->category_ids as $categoryId) {
        echo "var recommender = new wphellopack_Recommender('woocommerce-recommendations', {'categoryId' : '" . esc_html( $categoryId ) . "'});\n";
        echo "recommender.saveUserStep();\n";
    }
    echo "recommender.fetch();\n";
    echo '});';

    // End the script tag
    echo "</script>";
}

/**
 * Fetch recommendations from db
 *
 * @param array $data Options for the function.
 * @return string
 */
function wphellopack_get_recommendations(WP_REST_Request $request)
{
    $strategy = $request->get_param('strategy');
    $categories = explode('-', $strategy);
    wphellopack_display_recommended_products($categories);
}

/**
 * Displays recommended products
 *
 * @param array $categories Product categories the recommeneded products need to match to
 * @return string
 */
function wphellopack_display_recommended_products($categories = [])
{
    global $wpdb;

    $recommender = new WPHelloPack\Recommender();

    switch ( count($categories) ) {
        case 1:
            $products = $recommender->get_recommended_products_by_100_strategy( $categories );
            break;
        case 2:
            $products = $recommender->get_recommended_products_by_5050_strategy( $categories );
            break;
        case 3:
            $products = $recommender->get_recommended_products_by_661717_strategy( $categories );
            break;
    }

    // The recommended product view
    include_once 'templates/products/list.php';
}

/**
 * Displays recommended products holder
 *
 * @return void
 */
function wphellopack_display_recommended_products_holder()
{
    // The recommended products holder
    echo '
        <section class="products" id="woocommerce-recommendations">
            <center><img src="' . plugin_dir_url(__FILE__) . '/assets/images/preloader.gif" style="width: 50%;" /></center>
        </section>
    ';
}

add_action('rest_api_init', function () {
    register_rest_route('wphellopack-recommendations/v1', '/products', array(
        'methods' => 'GET',
        'callback' => 'wphellopack_get_recommendations',
        'permission_callback' => '__return_true',
    ));
});

add_action('woocommerce_after_single_product', 'wphellopack_display_recommended_products_holder');
add_action('woocommerce_after_single_product_summary', 'wphellopack_track_user_steps');
add_action('wp_enqueue_scripts', 'wphellopack_recommendations_js_init');