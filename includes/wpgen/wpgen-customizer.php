<?php
/**
 * Customizer options witch generate by wpgen
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Filter root option on customizer filter wpgen_filter_options.
 *
 * @param array $wpgen_defaults array from wpgen_options().
 *
 * @return array
 */
function wpgen_root_filter_options( $wpgen_defaults ) {

	$root_options  = get_option( 'root_options', false );
	$wpgen_options = array();

	// Theme colors.
	if ( $root_options && isset( $root_options['general-bg-color'] ) ) {

		$theme_style = get_style_by_saturate( explode( '-', $root_options['general-bg-color'] )[1] );

		$wpgen_options['general_color_scheme']        = $theme_style;
		$wpgen_options['general_header_color_scheme'] = $theme_style;
		$wpgen_options['general_menu_color_scheme']   = $theme_style;
		$wpgen_options['general_footer_color_scheme'] = $theme_style;

	}

	$data = get_option( 'wpgen_options', false );

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
add_filter( 'wpgen_filter_options', 'wpgen_root_filter_options', 20 );
