<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @package wpgen
 */

if ( !defined( 'ABSPATH' ) ) exit;

// Query WooCommerce activation
if ( !function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}
}


// Checks if the current page is a product archive
function is_product_archive() {
	if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
		return true;
	} else {
		return false;
	}
}


// WooCommerce setup function.
function wpgen_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	//add_theme_support( 'wc-product-gallery-zoom' );
	//add_theme_support( 'wc-product-gallery-lightbox' );
	//add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'wpgen_woocommerce_setup' );



// WooCommerce specific scripts & stylesheets.
function wpgen_woocommerce_scripts() {
	wp_enqueue_style( 'wpgen-woocommerce-style', get_template_directory_uri() . '/woocommerce.css' );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'wpgen-woocommerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'wpgen_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
//add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function wpgen_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'wpgen_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function wpgen_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'wpgen_woocommerce_products_per_page' );

/**
 * Product gallery thumnbail columns.
 *
 * @return integer number of columns.
 */
function wpgen_woocommerce_thumbnail_columns() {
	return 4;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'wpgen_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function wpgen_woocommerce_loop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'wpgen_woocommerce_loop_columns' );

/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function wpgen_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 3,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'wpgen_woocommerce_related_products_args' );

if ( !function_exists( 'wpgen_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function wpgen_woocommerce_product_columns_wrapper() {
		$columns = wpgen_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'wpgen_woocommerce_product_columns_wrapper', 40 );

if ( !function_exists( 'wpgen_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function wpgen_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'wpgen_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

if ( !function_exists( 'wpgen_woocommerce_wrapper_before' ) ) {
	// Wraps all WooCommerce content in wrappers which match the theme markup.
	function wpgen_woocommerce_wrapper_before() { ?>
		<main id="primary" <?php wpgen_content_area_classes(); ?> role="main">
	<?php }
}
add_action( 'woocommerce_before_main_content', 'wpgen_woocommerce_wrapper_before' );

if ( !function_exists( 'wpgen_woocommerce_wrapper_after' ) ) {
	// Closes the wrapping divs.
	function wpgen_woocommerce_wrapper_after() { ?>
		</main><!-- #main -->
	<?php }
}
add_action( 'woocommerce_after_main_content', 'wpgen_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'wpgen_woocommerce_header_cart' ) ) {
			wpgen_woocommerce_header_cart();
		}
	?>
 */

if ( !function_exists( 'wpgen_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function wpgen_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		wpgen_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'wpgen_woocommerce_cart_link_fragment' );

if ( !function_exists( 'wpgen_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function wpgen_woocommerce_cart_link() {
		?>
		<a class="cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'wpgen' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'wpgen' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span> <span class="count"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( !function_exists( 'wpgen_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function wpgen_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php wpgen_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}


// Breadcrumbs delimetr
function wpgen_change_breadcrumb_delimiter( $defaults ) {
	$defaults['delimiter'] = '  /  ';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'wpgen_change_breadcrumb_delimiter' );
