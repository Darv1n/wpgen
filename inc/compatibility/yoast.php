<?php
/**
 * Yoast SEO Compatibility File
 *
 * @link https://yoast.com/
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wpseo_googlebot', '__return_false' ); // Yoast SEO 14.x or newer.
add_filter( 'wpseo_bingbot', '__return_false' ); // Yoast SEO 14.x or newer.

// Yoast filters.
if ( function_exists( 'yoast_breadcrumb' ) ) {

	function wpseo_custom_breadcrumb_output_wrapper( $wrapper ) {
		$wrapper = 'ol';
		return $wrapper;
	}
	add_filter( 'wpseo_breadcrumb_output_wrapper', 'wpseo_custom_breadcrumb_output_wrapper' );

	function wpseo_custom_breadcrumb_single_link_wrapper( $wrapper ) {
		$wrapper = 'li';
		return $wrapper;
	}
	add_filter( 'wpseo_breadcrumb_single_link_wrapper', 'wpseo_custom_breadcrumb_single_link_wrapper' );

}

// Define the wpseo_opengraph_show_publish_date callback.
function wpgen_overwrite_yoast_publish_date( $content ) {
	$content = get_the_modified_date();
	return $content;
};
add_filter( 'wpseo_og_article_published_time', 'wpgen_overwrite_yoast_publish_date', 90, 2 );

// Добавляем закрывающий слег в теги rel="canonical".
function add_trail_slash_to_canonical_yoast( $canonical_url ) {
	return trailingslashit( $canonical_url );
}
add_filter( 'wpseo_canonical', 'add_trail_slash_to_canonical_yoast' );
