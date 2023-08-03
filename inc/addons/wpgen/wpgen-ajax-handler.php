<?php
/**
 * Ajax wpgen.
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_wpgen_form_action', 'ajax_wpgen_form_callback' );
add_action( 'wp_ajax_nopriv_wpgen_form_action', 'ajax_wpgen_form_callback' );
if ( ! function_exists( 'ajax_wpgen_form_callback' ) ) {

	/**
	 * Ajax callback handler wpgen form.
	 *
	 * @see get_root_styles()
	 *
	 * @return json
	 */
	function ajax_wpgen_form_callback() {

		// $_POST = wp_unslash( array_map( 'esc_attr', $_POST ) ); // Sanitize array.

		if ( isset( $_POST['type'] ) && $_POST['type'] === 'reset' ) {

			delete_option( 'wpgen_options' );
			delete_option( 'root_options' );

			return wp_send_json_success( get_root_styles() );
		} else {

			if ( ! empty( $_POST['content'] ) ) {
				parse_str( wp_unslash( $_POST['content'] ), $data ); // Create array that contains values of fields of filled form.

				$customizer_options = get_option( 'wpgen_options', false );

				if ( ! $customizer_options ) {
					$customizer_options = array();
				}

				foreach ( $data as $key => $value ) {
					if ( stripos( $key, 'customizer' ) !== false ) {

						$option = str_replace( 'customizer-', '', $key );
						$option = str_replace( '-', '_', $option );
						$option = str_replace( 'amp;', '', $option );

						$customizer_options[ sanitize_text_field( $option ) ] = sanitize_text_field( $value );
						unset( $data[ $key ] );
					}
				}

				update_option( 'wpgen_options', $customizer_options, true );
				update_option( 'root_options', $data, true );

				return wp_send_json_success( $data );
			}
		}

		return wp_send_json_error();
		wp_die();
	}
}
