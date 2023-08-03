<?php
/**
 * Customizer options witch generate by wpgen
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wpgen_filter_options_filter' ) ) {

	/**
	 * Filter root option on customizer filter wpgen_filter_options.
	 *
	 * @see wpgen_options()
	 *
	 * @param array $values Array with default wpgen options.
	 *
	 * @return array
	 */
	function wpgen_filter_options_filter( $values ) {

		$data          = get_option( 'wpgen_options', false );
		$custom_values = array();

		// Color scheme.
		if ( isset( $data['general_color_scheme'] ) ) {
			$custom_values['general_color_scheme'] = $data['general_color_scheme'];
		}

		// Container width.
		if ( isset( $data['general_container_width'] ) ) {
			$custom_values['general_container_width'] = $data['general_container_width'];
		}

		// Number of columns.
		if ( isset( $data['archive_page_columns'] ) ) {
			$custom_values['archive_page_columns'] = $data['archive_page_columns'];
		}

		// Menu position.
		if ( isset( $data['general_menu_position'] ) ) {
			$custom_values['general_menu_position'] = $data['general_menu_position'];
		}

		// Menu and up buttons.
		if ( isset( $data['general_menu_button_type'] ) ) {
			$custom_values['general_menu_button_type']       = $data['general_menu_button_type'];
			$custom_values['general_scroll_top_button_type'] = $data['general_menu_button_type'];
		}

		// Button style.
		if ( isset( $data['general_button_type'] ) ) {
			$custom_values['general_button_type'] = $data['general_button_type'];
		}

		return wp_parse_args( $custom_values, $values );
	}
}
add_filter( 'wpgen_filter_options', 'wpgen_filter_options_filter', 20 );
