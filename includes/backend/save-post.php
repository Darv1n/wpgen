<?php
/**
 * WordPress action hook save_post
 *
 * @link https://developer.wordpress.org/reference/hooks/the_content/
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'save_post', 'wpgen_save_post' );
if ( ! function_exists( 'wpgen_save_post' ) ) {

	/**
	 * Function for save_post action hook.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return void
	 */
	function wpgen_save_post( $post_id ) {

		// Write in post meta reading speed.
		update_post_meta( $post_id, 'read_time', read_time_estimate( get_post( $post_id )->post_content ) );
	}
}
