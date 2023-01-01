<?php
/**
 * Kama Postviews
 *
 * @link https://wp-kama.ru/plugin/kama-postviews
 *
 * @package source
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'save_post', 'kama_postviews_save_post' );
if ( ! function_exists( 'kama_postviews_save_post' ) ) {

	/**
	 * Function for 'save_post' action hook.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/save_post/
	 *
	 * @return void
	 */
	function kama_postviews_save_post( $post_id ) {

		// Add starting number of views for all published pages.
		$views = random_int( 20, 50 );

		add_post_meta( $post_id, 'views', $views, true );
		add_post_meta( $post_id, 'views_prev_month', $views, true );
	}
}

// Add kama postviews rating results before articles in meta list.
add_filter( 'get_wpgen_post_meta_list', 'add_kama_postviews_post_meta_list', 5 );
add_filter( 'get_wpgen_archive_meta_list', 'add_kama_postviews_post_meta_list', 5 );
if ( ! function_exists( 'add_kama_postviews_post_meta_list' ) ) {
	function add_kama_postviews_post_meta_list( $content ) {

		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$output = '';

		if ( is_plugin_active( 'kama-postviews/kama-postviews.php' ) ) {
			$output .= '<li class="meta__item meta__item_views-count data-title" data-title="' . esc_attr( __( 'Views', 'wpgen' ) ) . '">';
				$output .= get_fresh_kap_views( get_the_ID(), 'post' );
			$output .= '</li>';
		}

		return $content . $output;
	}
}

add_action( 'wp_enqueue_scripts', 'wp_enqueue_kama_postviews_styles', 11 );
if ( ! function_exists( 'wp_enqueue_kama_postviews_styles' ) ) {
	function wp_enqueue_kama_postviews_styles() {
		$css = '
			.fresh-views__month {
				display: none;
			}';

		wp_add_inline_style( 'common-styles', minify_css( $css ) );
	}
}