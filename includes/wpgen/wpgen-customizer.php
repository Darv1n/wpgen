<?php
/**
 * customizer options witch generate by wpgen
 *
 * @package wpgen
 */

if ( !defined( 'ABSPATH' ) )
	exit;

add_filter( 'wpgen_filter_options','wpgen_root_filter_options', 20 );
function wpgen_root_filter_options( $wpgen_defaults ) {

	$root_options = get_option( 'root_options', false );
	$wpgen_options = array();

	// цвета темы
	if ( $root_options && isset( $root_options['general-bg-color'] ) ) {

		$theme_style = get_style_by_saturate( explode( '-', $root_options['general-bg-color'])[1] );

		$wpgen_options['general_color_scheme'] = $theme_style;
		$wpgen_options['general_header_color_scheme'] = $theme_style;
		$wpgen_options['general_menu_color_scheme'] = $theme_style;
		$wpgen_options['general_footer_color_scheme'] = $theme_style;

	}



	$data = get_option( 'wpgen_options', false );


	// ширина контейнера
	if ( isset( $data['general_container_width'] ) ) {
		$wpgen_options['general_container_width'] = $data['general_container_width'];
	}

	// кол-во колонок
	if ( isset( $data['archive_page_columns'] ) ) {
		$wpgen_options['archive_page_columns'] = $data['archive_page_columns'];
	}

	// позиция меню
	if ( isset( $data['general_menu_position'] ) ) {
		$wpgen_options['general_menu_position'] = $data['general_menu_position'];
	}

	// кнопки меню и вверх
	if ( isset( $data['general_menu_button_type'] ) ) {
		$wpgen_options['general_menu_button_type'] = $data['general_menu_button_type'];
		$wpgen_options['general_scroll_top_button_type'] = $data['general_menu_button_type'];
	}

	// стиль кнопок
	if ( isset( $data['general_button_type'] ) ) {
		$wpgen_options['general_button_type'] = $data['general_button_type'];
	}

	return wp_parse_args( $wpgen_options, $wpgen_defaults );

}