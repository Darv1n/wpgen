<?php
/**
 * Setup root css styles in frontend
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wpgen_print_root_styles' ) ) {

	/**
	 * Output a string of the root styles in wp_head.
	 *
	 * @see get_root_styles()
	 *
	 * @return void
	 */
	function wpgen_print_root_styles() {

		$root_styles = get_root_styles();

		if ( ! is_array( $root_styles ) || empty( $root_styles ) ) {
			return;
		}

		$root_string = '';
		foreach ( $root_styles as $key => $root_value ) {
			$root_string .= '--' . $key . ': ' . $root_value . ';';
		}

		echo '<style id="wpgen-root">:root {' . esc_attr( $root_string ) . '}</style>';
	}
}
add_action( 'wp_head', 'wpgen_print_root_styles', 1 );

if ( ! function_exists( 'wpgen_enqueue_root_fonts' ) ) {

	/**
	 * Enqueue fonts.
	 *
	 * @see get_root_styles()
	 * @see get_root_selected_font()
	 *
	 * @return void
	 */
	function wpgen_enqueue_root_fonts() {

		$fonts        = array();
		$root_options = get_option( 'root_options', false );

		// Let's try to get the fonts from wp_options, which are generated by the wpgen form.
		if ( $root_options && isset( $root_options['primary-font'] ) && isset( $root_options['secondary-font'] ) ) {
			$fonts['primary']   = get_root_selected_font( $root_options['primary-font'] );
			$fonts['secondary'] = get_root_selected_font( $root_options['secondary-font'] );
		} else {
			$fonts['primary']   = get_root_styles( 'primaryFont' );
			$fonts['secondary'] = get_root_styles( 'secondaryFont' );
		}

		// Enqueue only one font if they are the same.
		if ( $fonts['primary'] === $fonts['secondary'] ) {
			wp_enqueue_style( 'primary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['primary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
		} else {
			wp_enqueue_style( 'primary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['primary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
			wp_enqueue_style( 'secondary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['secondary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'wpgen_enqueue_root_fonts' );

if ( ! function_exists( 'get_root_styles' ) ) {

	/**
	 * Returns string or array with root styles.
	 *
	 * @see get_root_defaults()
	 * @see get_root_selected_font()
	 * @see get_root_selected_value()
	 *
	 * @param string $control Key to get one value. Default return all array.
	 *                        Optional. Default null.
	 *
	 * @return string|string[]|false Return one value from the $converter array if $control exist.
	 *                               Return false, if the specified $control doesn't exist in in the $converter array.
	 *                               Default return all $converter array.
	 */
	function get_root_styles( $control = null ) {

		// Sanitize string (just to be safe).
		if ( ! is_null( $control ) ) {
			$control = get_title_slug( $control );
		}

		$root_styles    = array();
		$root_defaults  = get_root_defaults();
		$saturate_array = array( 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950 );

		foreach ( $root_defaults as $key => $root_default ) {
			if ( in_array( $key, array( 'primary-color', 'secondary-color', 'gray-color' ), true ) ) {
				foreach ( $saturate_array as $key_s => $saturate_value ) {
					$root_styles[ $key . '-' . $saturate_value ] = get_root_selected_value( $root_default . '-' . $saturate_value );
				}
			} elseif ( in_array( $key, array( 'primaryFont', 'secondaryFont' ), true ) ) {
				$root_styles[ $key ] = get_root_selected_font( $root_default );
			} elseif ( $key === 'btnSize' ) {
				$root_styles['buttonPaddingTop']  = explode( ' ', get_root_selected_value( $root_default ) )[0];
				$root_styles['buttonPaddingLeft'] = explode( ' ', get_root_selected_value( $root_default ) )[1];
			} elseif ( $key === 'svg-filter' ) {
				$root_styles[ $key ] = $root_default;
			} else {
				$root_styles[ $key ] = get_root_selected_value( $root_default );
			}
		}

		// Merge child and parent default options.
		$root_styles = apply_filters( 'get_root_styles', $root_styles );

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
