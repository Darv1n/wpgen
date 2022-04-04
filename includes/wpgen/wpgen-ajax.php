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

	$_POST = sanitize_post( $_POST, 'db' );

	// Проверяем nonce, а в случае если что-то пошло не так, то прерываем выполнение функции
/*	if ( !wp_verify_nonce( $_POST['security'], 'nonce-wpgen' ) ) {
		wp_die( 'Базовая защита не пройдена' );
	}*/

	// Создаём массив который содержит значения полей заполненной формы
	parse_str( $_POST['content'], $data );

	//return wp_send_json_success( $data );

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

	$updated = update_option( 'wpgen_options', $customizer_options, true );

	$updated = update_option( 'root_options', $data, true );

	return wp_send_json_success( $data );






/*	if ( isset( $data['name'] ) && !empty( $data['name'] ) ) {

		$title = get_title_slug( $data['name'] );
		$file_path = get_stylesheet_directory() . '/data/config/' . $title . '.json';
		$file_put_contents = file_put_contents( $file_path, json_encode( $data ), LOCK_EX );

		if ( $file_put_contents ) {

			if ( isset( $_POST['iter'] ) ) {
				$ver = get_title_slug( $_POST['iter'] );
			} else {
				$ver = 1;
			}

			$root_array = get_theme_root_style( null, 'array' );
			$style_file = get_theme_file_path( '/assets/css/common.min.css' );
			$user_id = get_current_user_id();
			$temp_style_file = get_stylesheet_directory() . '/data/temp/css/temp-user-' . $user_id . '.min.css';
			$temp_style_file_uri = get_stylesheet_directory_uri() . '/data/temp/css/temp-user-' . $user_id . '.min.css?ver=' . $ver;
			//vardump( $style_file );

			if ( file_exists( $style_file ) ) {
				
				$css_data = file_get_contents( $style_file );

				foreach ( $root_array as $key => $root_value ) {
					$css_data = str_replace( 'var(--' . $key . ')', $root_value, $css_data );
				}

				$file_put_contents = file_put_contents( $temp_style_file, $css_data, LOCK_EX );

				if ( $file_put_contents ) {
					return wp_send_json_success( $temp_style_file_uri );
				} else {
					return wp_send_json_error();
				}

			} else {
				return wp_send_json_error();
			}

		} else {
			return wp_send_json_error();
		}
			
	} else {
		return wp_send_json_error();
	}*/

	wp_die();

}
