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

	// цвета темы
	if ( $root_options && isset( $root_options['general-bg-color'] ) ) {

		$theme_style = get_style_by_saturate( explode( '-', $root_options['general-bg-color'])[1] );

		$wpgen_options['general_color_scheme'] = $theme_style;
		$wpgen_options['general_header_color_scheme'] = $theme_style;
		$wpgen_options['general_menu_color_scheme'] = $theme_style;
		$wpgen_options['general_footer_color_scheme'] = $theme_style;

	}

/*	// кнопки меню и вверх
	if ( isset( $data['customizer-menu-button-type'] ) ) {
		$wpgen_theme_defaults['general_menu_button_type'] = $data['customizer-menu-button-type'];
		$wpgen_theme_defaults['general_scroll_top_button_type'] = $data['customizer-menu-button-type'];
	}


	// ширина контейнера
	if ( isset( $data['customizer-container'] ) ) {

		$converter = array(
			'container-average' => 'average',
			'container-wide' => 'wide',
		);

		$wpgen_theme_defaults['general_container_width'] = strtr( $data['customizer-container'], $converter );
	}

	// кол-во колонок
	if ( isset( $data['customizer-columns'] ) ) {
		$wpgen_theme_defaults['archive_page_columns'] = $data['customizer-columns'];
	}*/


	return wp_parse_args( $wpgen_options, $wpgen_defaults );

}