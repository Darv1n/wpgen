<?php
/**
 * Customizer options witch generate by wpgen
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wpgen_filter_options', 'wpgen_root_filter_options', 20 );
if ( ! function_exists( 'wpgen_root_filter_options' ) ) {

	/**
	 * Filter root option on customizer filter wpgen_filter_options.
	 *
	 * @param array $wpgen_defaults array from wpgen_options().
	 *
	 * @return array
	 */
	function wpgen_root_filter_options( $wpgen_defaults ) {

		$data          = get_option( 'wpgen_options', false );
		$wpgen_options = array();

		// Color Scheme.
		if ( isset( $data['general_color_scheme'] ) ) {
			$wpgen_options['general_color_scheme'] = $data['general_color_scheme'];
		}

		// Container width.
		if ( isset( $data['general_container_width'] ) ) {
			$wpgen_options['general_container_width'] = $data['general_container_width'];
		}

		// Number of columns.
		if ( isset( $data['archive_page_columns'] ) ) {
			$wpgen_options['archive_page_columns'] = $data['archive_page_columns'];
		}

		// Menu position.
		if ( isset( $data['general_menu_position'] ) ) {
			$wpgen_options['general_menu_position'] = $data['general_menu_position'];
		}

		// Menu and up buttons.
		if ( isset( $data['general_menu_button_type'] ) ) {
			$wpgen_options['general_menu_button_type']       = $data['general_menu_button_type'];
			$wpgen_options['general_scroll_top_button_type'] = $data['general_menu_button_type'];
		}

		// Button style.
		if ( isset( $data['general_button_type'] ) ) {
			$wpgen_options['general_button_type'] = $data['general_button_type'];
		}

		return wp_parse_args( $wpgen_options, $wpgen_defaults );
	}
}
