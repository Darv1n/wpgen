<?php
/**
 * Root styles for wpgen
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


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

		// Возвращаем путой массив, если опций нет.
		if ( ! $data ) {
			return array();
		}

		// Цвета.
		$gpc  = explode( '-', $data['general-primary-color'] )[0];
		$gpcs = explode( '-', $data['general-primary-color'] )[1];
		$gsc  = explode( '-', $data['general-secondary-color'] )[0];
		$gscs = explode( '-', $data['general-secondary-color'] )[1];
		$gbc  = explode( '-', $data['general-bg-color'] )[0];
		$gbcs = explode( '-', $data['general-bg-color'] )[1];

		$theme_style = get_opposite_color_style_by_saturate( $gbcs );

		$grayColorDark    = $gbc . '-700';
		$grayColorDarken  = $gbc . '-600';
		$grayColor        = $gbc . '-500';
		$grayColorLighten = $gbc . '-400';
		$grayColorLight   = $gbc . '-300';

		$root_wpgen = array(
			'primaryColorDark'      => get_selected_value( $gpc . '-' . ( (int) $gpcs + 200 ) ),
			'primaryColorDarken'    => get_selected_value( $gpc . '-' . ( (int) $gpcs + 100 ) ),
			'primaryColor'          => get_selected_value( $data['general-primary-color'] ),
			'primaryColorLighten'   => get_selected_value( $gpc . '-' . ( (int) $gpcs - 100 ) ),
			'primaryColorLight'     => get_selected_value( $gpc . '-' . ( (int) $gpcs - 200 ) ),

			'secondaryColorDark'    => get_selected_value( $gsc . '-' . ( (int) $gscs + 200 ) ),
			'secondaryColorDarken'  => get_selected_value( $gsc . '-' . ( (int) $gscs + 100 ) ),
			'secondaryColor'        => get_selected_value( $data['general-secondary-color'] ),
			'secondaryColorLighten' => get_selected_value( $gsc . '-' . ( (int) $gscs - 100 ) ),
			'secondaryColorLight'   => get_selected_value( $gsc . '-' . ( (int) $gscs - 200 ) ),

			'grayColorDark'         => get_selected_value( $grayColorDark ),
			'grayColorDarken'       => get_selected_value( $grayColorDarken ),
			'grayColor'             => get_selected_value( $grayColor ),
			'grayColorLighten'      => get_selected_value( $grayColorLighten ),
			'grayColorLight'        => get_selected_value( $grayColorLight ),

			'bgColorDark'           => get_selected_value( $gbc . '-800' ),
			'bgColorDarken'         => get_selected_value( $gbc . '-900' ),
			'bgColorLighten'        => get_selected_value( $gbc . '-300' ),
			'bgColorLight'          => get_selected_value( $gbc . '-400' ),

			'whiteColor'            => get_selected_value( $gbc . '-50' ),
			'textColor'             => get_selected_value( $gbc . '-900' ),

			'elemBgColor'           => get_selected_value( $gbc . '-' . $data['elem-bg-saturate'] ),
		);

		// Шрифты.
		if ( isset( $data['primary-font'] ) ) {
			$root_wpgen['primaryFont'] = get_selected_font( $data['primary-font'] );
		}

		if ( isset( $data['secondary-font'] ) ) {
			$root_wpgen['secondaryFont'] = get_selected_font( $data['secondary-font'] );
		}

		// Цвет ссылок.
		if ( isset( $data['general-link-color'] ) ) {
			if ( $data['general-link-color'] === 'blue' ) {
				$link_color = $data['general-link-color'];
			} elseif ( $data['general-link-color'] === 'gray' ) {
				$link_color = explode( '-', $data['general-bg-color'] )[0];
			} else {
				$link_color = explode( '-', $data[ 'general-' . $data['general-link-color'] . '-color' ] )[0];
			}

			$root_wpgen['linkColorDark']  = get_selected_value( $link_color . '-600' );
			$root_wpgen['linkColor']      = get_selected_value( $link_color . '-500' );
			$root_wpgen['linkColorLight'] = get_selected_value( $link_color . '-400' );
		}

		// Цвет шрифтов на элементах.
		if ( isset( $data['elem-bg-saturate'] ) ) {
			if ( get_opposite_color_style_by_saturate( $data['elem-bg-saturate'] ) === 'white' ) {
				$root_wpgen['elemTextColor'] = get_selected_value( $gbc . '-50' );
			} else {
				$root_wpgen['elemTextColor'] = get_selected_value( $gbc . '-900' );
			}
		}

		// Размер кнопок.
		if ( isset( $data['btn-size'] ) ) {
			$btn_size                        = get_selected_value( $data['btn-size'] );
			$root_wpgen['buttonPaddingTop']  = explode( ' ', $btn_size )[0];
			$root_wpgen['buttonPaddingLeft'] = explode( ' ', $btn_size )[1];
		}

		// Радиус бордера кнопок.
		if ( isset( $data['btn-bd-radius'] ) ) {
			$root_wpgen['btnBdRadius'] = get_selected_value( $data['btn-bd-radius'] );
		}

		// Паддинги элементов.
		if ( isset( $data['elem-padding'] ) ) {
			$root_wpgen['elemPadding'] = get_selected_value( $data['elem-padding'] );
		}

		// Тени элементов.
		if ( isset( $data['elem-shadow'] ) ) {
			$root_wpgen['elemShadow']      = get_selected_value( $data['elem-shadow'] );
			$root_wpgen['elemShadowHover'] = str_replace( '0.15', '0.25', get_selected_value( $data['elem-shadow'] ) );
		}

		// Ширина бордера элементов.
		if ( isset( $data['elem-bd-width'] ) ) {
			$root_wpgen['elemBdWidth'] = get_selected_value( $data['elem-bd-width'] );
		}

		// Цвет бордера элементов.
		if ( isset( $data['elem-bd-color'] ) ) {
			$root_wpgen['elemBdColor'] = get_selected_value( explode( '-', $data[ 'general-' . $data['elem-bd-color'] . '-color' ] )[0] . '-' . $data['elem-bd-color-saturate'] );
		}

		// Радиус бордера элементов.
		if ( isset( $data['elem-bd-radius'] ) ) {
			$root_wpgen['elemBdRadius'] = get_selected_value( $data['elem-bd-radius'] );
		}

		// Ховер бордера элементов.
		if ( isset( $data['elem-bd-color-saturate'] ) ) {
			if ( get_opposite_color_style_by_saturate( $data['elem-bd-color-saturate'] ) === 'white' ) {
				$root_wpgen['elemBdColorHover'] = get_selected_value( explode( '-', $data[ 'general-' . $data['elem-bd-color'] . '-color' ] )[0] . '-' . get_prev_saturate( $data['elem-bd-color-saturate'] ) );
			} else {
				$root_wpgen['elemBdColorHover'] = get_selected_value( explode( '-', $data[ 'general-' . $data['elem-bd-color'] . '-color' ] )[0] . '-' . get_next_saturate( $data['elem-bd-color-saturate'] ) );
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
