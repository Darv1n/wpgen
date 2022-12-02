<?php
/**
 * Return main css array with root styles
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_root_styles' ) ) {

	/**
	 * Returns array with root styles (for tests and print in wp_add_inline_style)
	 *
	 * @param string $control array key to get one value.
	 *
	 * @return array
	 */
	function get_root_styles( $control = null ) {

		$root_styles   = array();
		$root_defaults = get_root_defaults();

		foreach ( $root_defaults as $key => $root_default ) {

			if ( in_array( $key, array( 'primaryFont', 'secondaryFont' ), true ) ) {
				$root_styles[ $key ] = get_selected_font( $root_default );
			} elseif ( $key === 'btnSize' ) {
				$root_styles['buttonPaddingTop']  = explode( ' ', get_selected_value( $root_default ) )[0];
				$root_styles['buttonPaddingLeft'] = explode( ' ', get_selected_value( $root_default ) )[1];
			} else {
				$root_styles[ $key ] = get_selected_value( $root_default );
			}
		}

		// Merge child and parent default options.
		$root_styles = apply_filters( 'root_styles_filter_options', $root_styles );

		// Return controls.
		if ( is_null( $control ) ) {
			return $root_styles;
		} elseif ( ! isset( $root_styles[ $control ] ) || empty( $root_styles[ $control ] ) ) {
			return false;
		} else {
			return $root_styles[ $control ];
		}
	}
}

/*
// Usage:
add_filter( 'root_styles_filter_options', 'source_root_styles_filter_options', 15 );
function source_root_styles_filter_options( $root_styles ) {

	$source_styles = array(
		'primaryFont'   => '\'Jost\'',
		'secondaryFont' => '\'Jost\'',
	);

	return wp_parse_args( $source_styles, $root_styles );
}
*/