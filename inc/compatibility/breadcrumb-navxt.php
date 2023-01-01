<?php
/**
 * Breadcrumb NavXT Compatibility File
 *
 * @link Plugin        https://wordpress.org/plugins/breadcrumb-navxt/
 * @link Documentation https://mtekk.us/code/breadcrumb-navxt/breadcrumb-navxt-doc/
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'bcn_breadcrumb_title', 'bcn_breadcrumb_title_callback', 3, 10 );
if ( ! function_exists( 'bcn_breadcrumb_title_callback' ) ) {
	function bcn_breadcrumb_title_callback( $title, $type, $id ) {
		if ( in_array( 'home', $type, true ) ) {
			$title = __( 'Home', 'wpgen' );
		}
		return $title;
	}
}

add_filter( 'bcn_display_attributes', 'bcn_display_attributes_callback', 10, 3 );
if ( ! function_exists( 'bcn_display_attributes_callback' ) ) {
	function bcn_display_attributes_callback( $attribs, $types, $id ) {
		$extra_attribs = array( 'class' => array( 'breadcrumbs-item' ) );

		// For the current item we need to add a little more info.
		if ( is_array( $types ) && in_array( 'current-item', $types ) ) {
			$extra_attribs['class'][]      = 'active';
			$extra_attribs['aria-current'] = array( 'page' );
		}

		$atribs_array = array();
		preg_match_all( '/([a-zA-Z]+)=["\']([a-zA-Z0-9\-\_ ]*)["\']/i', $attribs, $matches );

		if ( isset( $matches[1] ) ) {
			foreach ( $matches[1] as $key => $tag ) {
				if ( isset( $matches[2][ $key ] ) ) {
					$atribs_array[ $tag ] = explode( ' ', $matches[2][ $key ]);
				}
			}
		}

		$merged_attribs = array_merge_recursive( $atribs_array, $extra_attribs );
		$output         = '';

		foreach ( $merged_attribs as $tag => $vals ) {
			$output .= sprintf( ' %1$s="%2$s"', $tag, implode( ' ', $vals ) );
		}

		return $output;
	}
}

add_action( 'wp_enqueue_scripts', 'wp_enqueue_navxt_breadcrumb_styles' );
if ( ! function_exists( 'wp_enqueue_navxt_breadcrumb_styles' ) ) {
	function wp_enqueue_navxt_breadcrumb_styles() {
		$css = '
			.breadcrumbs_navxt .breadcrumbs-item:not(:last-child) {
				position: relative;
				margin-right: 1rem;
			}
			.breadcrumbs_navxt .breadcrumbs-item:not(:last-child)::before {
				position: absolute;
				content: \'/\';
				right: -.75rem;
			}';

		$css = minify_css( $css );

		wp_add_inline_style( 'common-styles', $css );
	}
}