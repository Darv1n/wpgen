<?php
/**
 * SEO actions.
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', 'the_wpgen_seo_meta_data', 1 );
if ( ! function_exists( 'the_wpgen_seo_meta_data' ) ) {

	/**
	 * Outputs the html markup metatags.
	 *
	 * @return void
	 */
	function the_wpgen_seo_meta_data() {

		$seo_defaults = get_wpgen_seo_meta_data(); // This functions in seo/seo-functions.php

		if ( ! is_array( $seo_defaults ) || empty( $seo_defaults ) ) {
			return;
		}

		if ( ! is_wpgen_seo_meta() ) {
			return;
		}

		$output = '';

		if ( isset( $seo_defaults['link'] ) && is_array( $seo_defaults['link'] ) && ! empty( $seo_defaults['link'] ) ) {
			foreach ( $seo_defaults['link'] as $rel => $link ) {
				$output .= '<link rel="' . $rel . '" href="' . $link . '" />' . "\r\n";
			}
		}

		if ( isset( $seo_defaults['description'] ) && ! empty( $seo_defaults['description'] ) ) {
			$output .= '<meta name="description" content="' . get_escape_title( $seo_defaults['description'] ) . '" />' . "\r\n";
		}

		if ( isset( $seo_defaults['title'] ) && ! empty( $seo_defaults['title'] ) ) {
			$output .= '<meta property="og:title" content="' . get_escape_title( $seo_defaults['title'] ) . '" />' . "\r\n";
		}

		if ( isset( $seo_defaults['description'] ) && ! empty( $seo_defaults['description'] ) ) {
			$output .= '<meta property="og:description" content="' . get_escape_title( $seo_defaults['description'] ) . '" />' . "\r\n";
		}

		if ( isset( $seo_defaults['property'] ) && is_array( $seo_defaults['property'] ) && ! empty( $seo_defaults['property'] ) ) {
			foreach ( $seo_defaults['property'] as $property => $content ) {
				$output .= '<meta property="' . $property . '" content="' . $content . '" />' . "\r\n";
			}
		}

		if ( isset( $seo_defaults['name'] ) && is_array( $seo_defaults['name'] ) && ! empty( $seo_defaults['name'] ) ) {
			foreach ( $seo_defaults['name'] as $name => $content ) {
				$output .= '<meta name="' . $name . '" content="' . $content . '" />' . "\r\n";
			}
		}

		echo $output;

		//vardump( $seo_defaults );
	}
}
