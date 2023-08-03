<?php
/**
 * Wpgen Theme Customizer
 *
 * @package wpgen
 */

if ( ! function_exists( 'get_wpgen_customizer_post_types' ) ) {

	/**
	 * Get customizer post types for constract section and fields.
	 *
	 * @return array
	 */
	function get_wpgen_customizer_post_types() {

		$post_types = array( 'post', 'project', 'service', 'product', 'event' );

		$post_types = apply_filters( 'get_wpgen_customizer_post_types', $post_types );

		return $post_types;
	}
}
