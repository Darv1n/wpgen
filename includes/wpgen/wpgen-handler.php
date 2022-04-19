<?php
/**
 * load more
 *
 * @package fertilizer
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'wp_ajax_ajax_wpgen', 'ajax_wpgen_callback' );
add_action( 'wp_ajax_nopriv_ajax_wpgen', 'ajax_wpgen_callback' );

// Обработчик
function ajax_wpgen_callback() {

	array_walk_recursive( $_POST, 'sanitize_form_field' ); // очистка массива
	parse_str( $_POST['content'], $data ); // Создаём массив который содержит значения полей заполненной формы

	if ( $_POST['type'] === 'reset' ) {

		delete_option( 'wpgen_options' );
		delete_option( 'root_options' );

		return wp_send_json_success( get_root_styles() );

	} else {

		$customizer_options = get_option( 'wpgen_options', false );

		if ( !$customizer_options ) {
			$customizer_options = array();
		}
		
		foreach ( $data as $key => $value ) {
			if ( stripos( $key, 'customizer' ) !== false ) {

				$option = str_replace( 'customizer-', '', $key );
				$option = str_replace( '-', '_', $option );

				$customizer_options[$option] = $value;
				unset( $data[$key] );

			}
		}

		update_option( 'wpgen_options', $customizer_options, true );
		update_option( 'root_options', $data, true );

		return wp_send_json_success( $data );

	}


	wp_die();

}
