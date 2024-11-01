<?php
/**
 * Loop wphellopack Recommended Products
 *
 *
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>

<?php
    $heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Recommended products', 'wphellopack-recommendations' ) );

    if ( $heading ) :
?>
    <h2>
        <?php echo esc_html( $heading ); ?>
    </h2>
<?php endif; ?>

<ul class="wphellopack-recommendations-products products columns-4">
    
    <?php foreach ($products as $product): ?>
        <?php 
            $product_title = get_the_title($product->ID);
            $product = wc_get_product($product->ID);
        ?>
        <li class="<?php echo implode(' ', wc_get_product_class( '', $product )) ?>">
                <a href="<?php echo get_the_permalink($product->id) ?>" class="wphellopack-recommendations-product-link">
                    <?php woocommerce_template_loop_product_thumbnail(); ?>
                    <?php echo '<h2 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . '">' . esc_html( $product_title ) . '</h2>'; ?>
                    <?php woocommerce_template_loop_price(); ?>
                </a>
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </li>
    <?php endforeach; ?>

</ul>

<style>
    .wphellopack-recommendations-product-link {
        text-decoration: none;
    }
    .wphellopack-recommendations-products {
        margin-top: 20px;
    }
</style>