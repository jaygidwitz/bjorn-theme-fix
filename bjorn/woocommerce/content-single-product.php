<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$bjorn_theme_options = bjorn_get_theme_options();

// Sidebar position
// Demo settings
if ( defined('DEMO_MODE') && isset($_GET['woocommerce_product_sidebar_position']) ) {
  $bjorn_theme_options['woocommerce_product_sidebar_position'] = $_GET['woocommerce_product_sidebar_position'];
}

if(isset($bjorn_theme_options['woocommerce_product_sidebar_position'])) {
	$page_sidebarposition = $bjorn_theme_options['woocommerce_product_sidebar_position'];
} else {
	$page_sidebarposition = 'disable';
}

if(is_active_sidebar( 'woocommerce-sidebar' ) && ($page_sidebarposition <> 'disable')) {
  $span_class = 'col-md-9';
}
else {
  $span_class = 'col-md-12';
}

// Change default notices position
remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10, 0 );
add_action( 'woocommerce_before_single_product_summary', 'wc_print_notices', 10, 0 );

?>

<?php
	/**
	 * woocommerce_before_single_product hook.
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<div class="container-fluid container-page-item-title">
	<div class="row">
		<div class="col-md-12">
			<div class="page-item-title-archive">
				<?php woocommerce_breadcrumb(); ?>
				<h1 itemprop="name"><?php the_title(); ?></h1>
			</div>
		</div>
	</div>
</div>

<div class="page-container container">
    	<div class="row">
    		<?php if ( is_active_sidebar( 'woocommerce-sidebar' ) && ( $page_sidebarposition == 'left')) : ?>
			<div class="col-md-3 main-sidebar sidebar">
				<ul id="woocommerce-sidebar">
				  <?php dynamic_sidebar( 'woocommerce-sidebar' ); ?>
				</ul>
			</div>
			<?php endif; ?>
    		<div class="<?php echo esc_attr($span_class); ?>">

<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook.
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			// Remove default product title
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5, 0 );
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->



<?php do_action( 'woocommerce_after_single_product' ); ?>

			</div>

		<?php if ( is_active_sidebar( 'woocommerce-sidebar' ) && ( $page_sidebarposition == 'right')) : ?>
		<div class="col-md-3 main-sidebar sidebar">
			<ul id="woocommerce-sidebar">
			  <?php dynamic_sidebar( 'woocommerce-sidebar' ); ?>
			</ul>
		</div>
		<?php endif; ?>

		</div>
	</div>
