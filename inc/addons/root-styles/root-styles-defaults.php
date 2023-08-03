<?php
/**
 * Default array with root styles
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_root_defaults' ) ) {

	/**
	 * Return string or array with the default root styles.
	 *
	 * @param string $control Key to get one value. Default return all array.
	 *                        Optional. Default null.
	 *
	 * @return string|string[]|false Return one value from the $converter array if $control exist.
	 *                               Return false, if the specified $control doesn't exist in in the $converter array.
	 *                               Default return all $converter array.
	 */
	function get_root_defaults( $control = null ) {

		// Sanitize string (just to be safe).
		if ( ! is_null( $control ) ) {
			$control = get_title_slug( $control );
		}

		// Main converter array.
		$converter = array(
			'primaryFont'     => 'montserrat',
			'secondaryFont'   => 'montserrat',

			'primary-color'   => 'sky',
			'secondary-color' => 'orange',
			'gray-color'      => 'slate',

			'elemBgColor'     => 'slate-200',
			'elemTextColor'   => 'slate-950',
			'elemPadding'     => 'p-3',
			'elemShadow'      => 'shadow-md',
			'elemShadowHover' => 'shadow-lg',
			'elemBdColor'     => 'sky-300',
			'elemBdColorHover'=> 'sky-400',
			'elemBdWidth'     => 'border-2',
			'elemBdRadius'    => 'rounded-md',

			'btnSize'         => 'btn',
			'btnBdWidth'      => 'border-2',
			'btnBdRadius'     => 'rounded-md',
		);

		$converter['gray-color'] = apply_filters( 'get_root_default_gray', $converter['gray-color'] );

		if ( wpgen_options( 'general_color_scheme' ) === 'black' ) {
			$converter['primary-bg-color']         = $converter['gray-color'] . '-950';
			$converter['primary-bg-color-hover']   = $converter['gray-color'] . '-900';
			$converter['primary-bd-color']         = $converter['gray-color'] . '-800';
			$converter['primary-bd-color-hover']   = $converter['gray-color'] . '-700';
			$converter['primary-gray-color']       = $converter['gray-color'] . '-300';
			$converter['primary-gray-color-hover'] = $converter['gray-color'] . '-400';
			$converter['primary-text-color']       = $converter['gray-color'] . '-50';
			$converter['white-color']              = $converter['gray-color'] . '-50';
			$converter['svg-filter']               = 'invert(100%)';
		} elseif ( wpgen_options( 'general_color_scheme' ) === 'dark' ) {
			$converter['primary-bg-color']         = $converter['gray-color'] . '-800';
			$converter['primary-bg-color-hover']   = $converter['gray-color'] . '-900';
			$converter['primary-bd-color']         = $converter['gray-color'] . '-900';
			$converter['primary-bd-color-hover']   = $converter['gray-color'] . '-950';
			$converter['primary-gray-color']       = $converter['gray-color'] . '-300';
			$converter['primary-gray-color-hover'] = $converter['gray-color'] . '-400';
			$converter['primary-text-color']       = $converter['gray-color'] . '-50';
			$converter['white-color']              = $converter['gray-color'] . '-50';
			$converter['svg-filter']               = 'invert(100%)';
		} elseif ( wpgen_options( 'general_color_scheme' ) === 'light' ) {
			$converter['primary-bg-color']         = $converter['gray-color'] . '-200';
			$converter['primary-bg-color-hover']   = $converter['gray-color'] . '-300';
			$converter['primary-bd-color']         = $converter['gray-color'] . '-300';
			$converter['primary-bd-color-hover']   = $converter['gray-color'] . '-400';
			$converter['primary-gray-color']       = $converter['gray-color'] . '-500';
			$converter['primary-gray-color-hover'] = $converter['gray-color'] . '-600';
			$converter['primary-text-color']       = $converter['gray-color'] . '-950';
			$converter['white-color']              = $converter['gray-color'] . '-50';
			$converter['svg-filter']               = 'invert(0%)';
		} else {
			$converter['primary-bg-color']         = $converter['gray-color'] . '-50';
			$converter['primary-bg-color-hover']   = $converter['gray-color'] . '-200';
			$converter['primary-bd-color']         = $converter['gray-color'] . '-300';
			$converter['primary-bd-color-hover']   = $converter['gray-color'] . '-400';
			$converter['primary-gray-color']       = $converter['gray-color'] . '-500';
			$converter['primary-gray-color-hover'] = $converter['gray-color'] . '-600';
			$converter['primary-text-color']       = $converter['gray-color'] . '-950';
			$converter['white-color']              = $converter['gray-color'] . '-50';
			$converter['svg-filter']               = 'invert(0%)';
		}

		$converter = apply_filters( 'get_root_defaults', $converter );

		// Return controls.
		if ( is_null( $control ) ) {
			return $converter;
		} elseif ( ! isset( $converter[ $control ] ) || empty( $converter[ $control ] ) ) {
			return false;
		} else {
			return $converter[ $control ];
		}
	}
}
