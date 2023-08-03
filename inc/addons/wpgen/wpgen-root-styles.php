<?php
/**
 * Root styles for wpgen
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wpgen_add_query_vars' ) ) {

	/**
	 * Function for query_vars filter-hook.
	 * 
	 * @param array $public_query_vars The array of allowed query variable names.
	 *
	 * @return array
	 */
	function wpgen_add_query_vars( $qvars ) {

		$qvars[] = 'wpgen';

		return array_unique( $qvars );
	}
}
add_filter( 'query_vars', 'wpgen_add_query_vars' );

if ( ! function_exists( 'wpgen_get_root_styles' ) ) {

	/**
	 * Filter options in get_root_styles() function.
	 *
	 * @param array $root_styles array of css styles
	 *
	 * @return void
	 */
	function wpgen_get_root_styles( $root_styles ) {

		$wpgen_styles = get_wpgen_root_style();

		if ( is_array( $wpgen_styles ) && ! empty( $wpgen_styles ) ) {
			$root_styles = wp_parse_args( $wpgen_styles, $root_styles );
		}

		return $root_styles;
	}
}
add_filter( 'get_root_styles', 'wpgen_get_root_styles', 30 );

if ( ! function_exists( 'get_wpgen_root_style' ) ) {

	/**
	 * Return string with root styles.
	 *
	 * @param string $control key to get one value.
	 *
	 * @return string
	 */
	function get_wpgen_root_style( $control = null ) {

		$data = get_option( 'root_options', false );

		// Return an empty array if there are no options.
		if ( ! $data ) {
			return array();
		}

		$root_wpgen     = array();
		$saturate_array = array( 50, 100, 200, 300, 400, 500, 600, 700, 800, 900 );

		if ( isset( $data['general-primary-color'] ) && isset( $data['general-secondary-color'] ) && isset( $data['general-gray-color'] ) ) {
			foreach ( $saturate_array as $key => $saturate_value ) {
				$root_wpgen = array_merge( $root_wpgen, array(
						'primary-color-' . $saturate_value   => get_root_selected_value( $data['general-primary-color'] . '-' . $saturate_value ),
						'secondary-color-' . $saturate_value => get_root_selected_value( $data['general-secondary-color'] . '-' . $saturate_value ),
						'gray-color-' . $saturate_value      => get_root_selected_value( $data['general-gray-color'] . '-' . $saturate_value ),
				) );
			}

			if ( isset( $data['elem-bg-saturate'] ) ) {
				$root_wpgen = array_merge( $root_wpgen, array(
					'white-color' => get_root_selected_value( $data['general-gray-color'] . '-50' ),
					'elemBgColor' => get_root_selected_value( $data['general-gray-color'] . '-' . $data['elem-bg-saturate'] ),
				) );

				if ( wpgen_options( 'general_color_scheme' ) === 'black' ) {
					$root_wpgen['primary-bg-color']         = get_root_selected_value( $data['general-gray-color'] . '-950' );
					$root_wpgen['primary-bg-color-hover']   = get_root_selected_value( $data['general-gray-color'] . '-900' );
					$root_wpgen['primary-bd-color']         = get_root_selected_value( $data['general-gray-color'] . '-800' );
					$root_wpgen['primary-bd-color-hover']   = get_root_selected_value( $data['general-gray-color'] . '-700' );
					$root_wpgen['primary-gray-color']       = get_root_selected_value( $data['general-gray-color'] . '-300' );
					$root_wpgen['primary-gray-color-hover'] = get_root_selected_value( $data['general-gray-color'] . '-400' );
					$root_wpgen['primary-text-color']       = get_root_selected_value( $data['general-gray-color'] . '-50' );
				} elseif ( wpgen_options( 'general_color_scheme' ) === 'dark' ) {
					$root_wpgen['primary-bg-color']         = get_root_selected_value( $data['general-gray-color'] . '-800' );
					$root_wpgen['primary-bg-color-hover']   = get_root_selected_value( $data['general-gray-color'] . '-900' );
					$root_wpgen['primary-bd-color']         = get_root_selected_value( $data['general-gray-color'] . '-900' );
					$root_wpgen['primary-bd-color-hover']   = get_root_selected_value( $data['general-gray-color'] . '-950' );
					$root_wpgen['primary-gray-color']       = get_root_selected_value( $data['general-gray-color'] . '-300' );
					$root_wpgen['primary-gray-color-hover'] = get_root_selected_value( $data['general-gray-color'] . '-400' );
					$root_wpgen['primary-text-color']       = get_root_selected_value( $data['general-gray-color'] . '-50' );
				} elseif ( wpgen_options( 'general_color_scheme' ) === 'light' ) {
					$root_wpgen['primary-bg-color']         = get_root_selected_value( $data['general-gray-color'] . '-200' );
					$root_wpgen['primary-bg-color-hover']   = get_root_selected_value( $data['general-gray-color'] . '-300' );
					$root_wpgen['primary-bd-color']         = get_root_selected_value( $data['general-gray-color'] . '-300' );
					$root_wpgen['primary-bd-color-hover']   = get_root_selected_value( $data['general-gray-color'] . '-400' );
					$root_wpgen['primary-gray-color']       = get_root_selected_value( $data['general-gray-color'] . '-500' );
					$root_wpgen['primary-gray-color-hover'] = get_root_selected_value( $data['general-gray-color'] . '-600' );
					$root_wpgen['primary-text-color']       = get_root_selected_value( $data['general-gray-color'] . '-950' );
				} else {
					$root_wpgen['primary-bg-color']         = get_root_selected_value( $data['general-gray-color'] . '-50' );
					$root_wpgen['primary-bg-color-hover']   = get_root_selected_value( $data['general-gray-color'] . '-200' );
					$root_wpgen['primary-bd-color']         = get_root_selected_value( $data['general-gray-color'] . '-300' );
					$root_wpgen['primary-bd-color-hover']   = get_root_selected_value( $data['general-gray-color'] . '-400' );
					$root_wpgen['primary-gray-color']       = get_root_selected_value( $data['general-gray-color'] . '-500' );
					$root_wpgen['primary-gray-color-hover'] = get_root_selected_value( $data['general-gray-color'] . '-600' );
					$root_wpgen['primary-text-color']       = get_root_selected_value( $data['general-gray-color'] . '-950' );
				}
			}

			// Elements text color.
			if ( isset( $data['elem-bg-saturate'] ) ) {
				if ( get_opposite_color_style_by_saturate( $data['elem-bg-saturate'] ) === 'white' ) {
					$root_wpgen['elemTextColor'] = get_root_selected_value( $data['general-gray-color'] . '-50' );
				} else {
					$root_wpgen['elemTextColor'] = get_root_selected_value( $data['general-gray-color'] . '-900' );
				}
			}
		}

		// Fonts.
		if ( isset( $data['primary-font'] ) ) {
			$root_wpgen['primaryFont'] = get_root_selected_font( $data['primary-font'] );
		}

		if ( isset( $data['secondary-font'] ) ) {
			$root_wpgen['secondaryFont'] = get_root_selected_font( $data['secondary-font'] );
		}

		// Link color.
		if ( isset( $data['general-link-color'] ) ) {
			if ( $data['general-link-color'] === 'blue' ) {
				$link_color = $data['general-link-color'];
			} else {
				$link_color = $data['general-' . $data['general-link-color'] . '-color'];
			}

			$root_wpgen['linkColorDark']  = get_root_selected_value( $link_color . '-600' );
			$root_wpgen['linkColor']      = get_root_selected_value( $link_color . '-500' );
			$root_wpgen['linkColorLight'] = get_root_selected_value( $link_color . '-400' );
		}

		// Button size.
		if ( isset( $data['btn-size'] ) ) {
			$btn_size                        = get_root_selected_value( $data['btn-size'] );
			$root_wpgen['buttonPaddingTop']  = explode( ' ', $btn_size )[0];
			$root_wpgen['buttonPaddingLeft'] = explode( ' ', $btn_size )[1];
		}

		// Button border width.
		if ( isset( $data['btn-bd-width'] ) ) {
			$root_wpgen['btnBdWidth'] = get_root_selected_value( $data['btn-bd-width'] );
		}

		// Button border radius.
		if ( isset( $data['btn-bd-radius'] ) ) {
			$root_wpgen['btnBdRadius'] = get_root_selected_value( $data['btn-bd-radius'] );
		}

		// Elements padding.
		if ( isset( $data['elem-padding'] ) ) {
			$root_wpgen['elemPadding'] = get_root_selected_value( $data['elem-padding'] );
		}

		// Elements shadows.
		if ( isset( $data['elem-shadow'] ) ) {
			$root_wpgen['elemShadow']      = get_root_selected_value( $data['elem-shadow'] );
			$root_wpgen['elemShadowHover'] = str_replace( '0.15', '0.25', get_root_selected_value( $data['elem-shadow'] ) );
		}

		// Elements border width.
		if ( isset( $data['elem-bd-width'] ) ) {
			$root_wpgen['elemBdWidth'] = get_root_selected_value( $data['elem-bd-width'] );
		}

		// Elements border color.
		if ( isset( $data['elem-bd-color'] ) ) {
			$root_wpgen['elemBdColor'] = get_root_selected_value( $data[ 'general-' . $data['elem-bd-color'] . '-color' ] . '-' . $data['elem-bd-color-saturate'] );
		}

		// Elements border radius.
		if ( isset( $data['elem-bd-radius'] ) ) {
			$root_wpgen['elemBdRadius'] = get_root_selected_value( $data['elem-bd-radius'] );
		}

		// Elements border color hover.
		if ( isset( $data['elem-bd-color-saturate'] ) ) {
			if ( get_opposite_color_style_by_saturate( $data['elem-bd-color-saturate'] ) === 'white' ) {
				$root_wpgen['elemBdColorHover'] = get_root_selected_value( $data[ 'general-' . $data['elem-bd-color'] . '-color' ] . '-' . get_prev_saturate( $data['elem-bd-color-saturate'] ) );
			} else {
				$root_wpgen['elemBdColorHover'] = get_root_selected_value( $data[ 'general-' . $data['elem-bd-color'] . '-color' ] . '-' . get_next_saturate( $data['elem-bd-color-saturate'] ) );
			}
		}

		// Return controls.
		if ( is_null( $control ) ) {
			return $root_wpgen;
		} elseif ( ! isset( $root_wpgen[ $control ] ) || empty( $root_wpgen[ $control ] ) ) {
			return false;
		} else {
			return $root_wpgen[ $control ];
		}
	}
}
