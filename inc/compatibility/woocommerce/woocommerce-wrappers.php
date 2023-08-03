<?php
/**
 * WooCommerce wrappers
 *
 * @link https://woocommerce.com/
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'woocommerce_product_loop_start', 'woocommerce_product_loop_start_filter' );
if ( ! function_exists( 'woocommerce_product_loop_start_filter' ) ) {

	/**
	 * Function for woocommerce_product_loop_start filter-hook.
	 * 
	 * @param  $ob_get_clean 
	 *
	 * @return string
	 */
	function woocommerce_product_loop_start_filter( $ob_get_clean ) {

		$ob_get_clean = '<ul class="products row row-' . esc_attr( wc_get_loop_prop( 'columns' ) ) . '-col list-unstyled">';

		return $ob_get_clean;
	}
}

add_filter( 'woocommerce_product_loop_end', 'woocommerce_product_loop_end_filter' );
if ( ! function_exists( 'woocommerce_product_loop_end_filter' ) ) {

	/**
	 * Function for woocommerce_product_loop_end filter-hook.
	 * 
	 * @param  $ob_get_clean 
	 *
	 * @return string
	 */
	function woocommerce_product_loop_end_filter( $ob_get_clean ) {

		$ob_get_clean = '</ul>';

		return $ob_get_clean;
	}
}


// Короче. Тут проблема, что в шаблонах woocommerce структура ul>li
// У нас структура    .row > .col > article.post
// Мы переделываем на ul.row > li.col > article.post

add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_before_shop_loop_item_wrapper_start', 1 );
if ( ! function_exists( 'woocommerce_before_shop_loop_item_wrapper_start' ) ) {

	/**
	 * Function for woocommerce_before_shop_loop_item action-hook.
	 * 
	 * @return void
	 */
	function woocommerce_before_shop_loop_item_wrapper_start() {

		global $product;

		$classes = wc_get_product_class( '', $product );
		$classes = array_diff( $classes, get_wpgen_archive_page_columns_classes( null, '', wc_get_default_products_per_row() ) );

		echo '<article id="post-' . get_the_ID() . '" class="' . esc_attr( implode( ' ', array_unique( $classes ) ) ) . '">';
	}
}

add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_after_shop_loop_item_wrapper_end', 99 );
if ( ! function_exists( 'woocommerce_after_shop_loop_item_wrapper_end' ) ) {

	/**
	 * Function for woocommerce_after_shop_loop_item action-hook.
	 * 
	 * @return void
	 */
	function woocommerce_after_shop_loop_item_wrapper_end() {
		echo '</article>';
	}
}

add_filter( 'woocommerce_post_class', 'woocommerce_post_class_filter', 10, 2 );
if ( ! function_exists( 'woocommerce_post_class_filter' ) ) {

	/**
	 * Function for woocommerce_post_class filter-hook.
	 * 
	 * @param array      $classes Array of CSS classes.
	 * @param WC_Product $product Product object.
	 *
	 * @return array
	 */
	function woocommerce_post_class_filter( $classes, $product ) {

		if ( is_product_archive() ) {

			if ( is_int_even( wc_get_loop_prop( 'loop', '' ) ) ) {
				$classes = get_wpgen_archive_page_columns_classes( wc_get_loop_prop( 'loop', null ), $classes, wc_get_default_products_per_row() );
			} else {
				$classes = get_wpgen_archive_page_columns_classes( wc_get_loop_prop( 'loop', null ), '', wc_get_default_products_per_row() );
			}
		}

		return $classes;
	}
}