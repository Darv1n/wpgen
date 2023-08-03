<?php
/**
 * Rate my Post
 *
 * @link https://wordpress.org/plugins/rate-my-post/
 *
 * @package source
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'rmp_save_post' ) ) {

	/**
	 * Function for 'save_post' action hook.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @link https://developer.wordpress.org/reference/hooks/save_post/
	 *
	 * @return void
	 */
	function rmp_save_post( $post_id ) {

		// Add a starting rating for all published pages.
		add_post_meta( $post_id, 'rmp_vote_count', 1, true );
		add_post_meta( $post_id, 'rmp_rating_val_sum', 5, true );
	}
}
add_action( 'save_post', 'rmp_save_post' );

// Add rate my post rating results before articles in meta list.
if ( ! function_exists( 'add_rmp_result_post_meta_list' ) ) {
	function add_rmp_result_post_meta_list( $content ) {

		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		$output = '';

		if ( is_plugin_active( 'rate-my-post/rate-my-post.php' ) ) {
			$output .= '<li class="meta__item meta__item_rating-count data-title" data-title="' . __( 'Rating', 'source' ) . '">';
				$output .= do_shortcode( '[ratemypost-result]' );
			$output .= '</li>';
		}

		return $content . $output;
	}
}
add_filter( 'get_wpgen_post_meta_list', 'add_rmp_result_post_meta_list', 80 );

// Add rate my post widget after articles.
if ( ! function_exists( 'add_rmp_shortcode_after_article_post' ) ) {
	function add_rmp_shortcode_after_article_post() {

		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( is_plugin_active( 'rate-my-post/rate-my-post.php' ) ) {
			echo do_shortcode( '[ratemypost]' );
		}

	}
}
add_action( 'wpgen_after_single_content_part', 'add_rmp_shortcode_after_article_post', 20 );
