<?php
/**
 * save_post WordPress function
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'save_post', 'wpgen_save_post' );
if ( !function_exists( 'wpgen_save_post' ) ) {
	function wpgen_save_post( $post_id ) {

		// пишем в мету скорость чтения
		update_post_meta( $post_id, 'read_time', read_time_estimate( get_post( $post_id )->post_content ) );

	}
}
