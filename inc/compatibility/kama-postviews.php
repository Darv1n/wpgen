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
add_action( 'save_post', 'kama_postviews_save_post' );

if ( ! function_exists( 'kama_postviews_post_meta_list' ) ) {

	// Add kama postviews rating results before articles in meta list.
	function kama_postviews_post_meta_list( $content ) {

		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( is_plugin_active( 'kama-postviews/kama-postviews.php' ) ) { ?>
			<li class="post-meta__item icon icon_before icon_eye data-title" data-title="<?php _e( 'Views', 'wpgen' ); ?>">
				<?php echo get_fresh_kap_views( get_the_ID(), 'post' ); ?>
			</li>
		<?php }
	}
}
add_action( 'wpgen_after_single_entry_post_meta', 'kama_postviews_post_meta_list', 5 );
add_action( 'wpgen_after_archive_entry_post_meta', 'kama_postviews_post_meta_list', 5 );

if ( ! function_exists( 'wp_enqueue_kama_postviews_styles' ) ) {

	// Enqueue kama postviews styles.
	function wp_enqueue_kama_postviews_styles() {
		$css = '
			.fresh-views__month {
				display: none;
			}';

		wp_add_inline_style( 'common-styles', minify_css( $css ) );
	}
}
add_action( 'wp_enqueue_scripts', 'wp_enqueue_kama_postviews_styles', 11 );