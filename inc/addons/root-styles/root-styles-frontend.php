<?php
/**
 * Setup root css styles in frontend
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_head', 'wpgen_print_root_styles', 1 );
if ( ! function_exists( 'wpgen_print_root_styles' ) ) {

	/**
	 * Output a string of root styles in wp_head.
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

add_action( 'wp_enqueue_scripts', 'wpgen_enqueue_fonts' );
if ( ! function_exists( 'wpgen_enqueue_fonts' ) ) {

	/**
	 * Шрифты (пытаемся получить их из опций, которые сгенерил wpgen или берем дефолтные).
	 */
	function wpgen_enqueue_fonts() {

		$fonts        = array();
		$root_options = get_option( 'root_options', false );

		if ( $root_options && isset( $root_options['primary-font'] ) && isset( $root_options['secondary-font'] ) ) {
			$fonts['primary']   = get_selected_font( $root_options['primary-font'] );
			$fonts['secondary'] = get_selected_font( $root_options['secondary-font'] );
		} else {
			$fonts['primary']   = get_root_styles( 'primaryFont' );
			$fonts['secondary'] = get_root_styles( 'secondaryFont' );
		}

		if ( ! is_wpgen_active() && $fonts['primary'] === $fonts['secondary'] ) {
			wp_enqueue_style( 'primary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['primary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
		} else {
			wp_enqueue_style( 'primary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['primary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
			wp_enqueue_style( 'secondary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['secondary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
		}
	}
}
