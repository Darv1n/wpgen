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
	 * Return array with the default root styles.
	 *
	 * @param string $control array key to get one value.
	 *
	 * @return array
	 */
	function get_root_defaults( $control = null ) {

		$root_defaults = array(
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

			'btnSize'         => 'btn-md',
			'btnBdWidth'      => 'border-2',
			'btnBdRadius'     => 'rounded-md',
		);

		$root_defaults['gray-color'] = apply_filters( 'get_root_default_gray', $root_defaults['gray-color'] );

		if ( wpgen_options( 'general_color_scheme' ) === 'black' ) {
			$root_defaults['primary-bg-color']         = $root_defaults['gray-color'] . '-950';
			$root_defaults['primary-bg-color-hover']   = $root_defaults['gray-color'] . '-900';
			$root_defaults['primary-bd-color']         = $root_defaults['gray-color'] . '-800';
			$root_defaults['primary-bd-color-hover']   = $root_defaults['gray-color'] . '-700';
			$root_defaults['primary-gray-color']       = $root_defaults['gray-color'] . '-300';
			$root_defaults['primary-gray-color-hover'] = $root_defaults['gray-color'] . '-400';
			$root_defaults['primary-text-color']       = $root_defaults['gray-color'] . '-50';
			$root_defaults['white-color']              = $root_defaults['gray-color'] . '-50';
			$root_defaults['svg-filter']               = 'invert(100%)';
		} elseif ( wpgen_options( 'general_color_scheme' ) === 'dark' ) {
			$root_defaults['primary-bg-color']         = $root_defaults['gray-color'] . '-800';
			$root_defaults['primary-bg-color-hover']   = $root_defaults['gray-color'] . '-900';
			$root_defaults['primary-bd-color']         = $root_defaults['gray-color'] . '-900';
			$root_defaults['primary-bd-color-hover']   = $root_defaults['gray-color'] . '-950';
			$root_defaults['primary-gray-color']       = $root_defaults['gray-color'] . '-300';
			$root_defaults['primary-gray-color-hover'] = $root_defaults['gray-color'] . '-400';
			$root_defaults['primary-text-color']       = $root_defaults['gray-color'] . '-50';
			$root_defaults['white-color']              = $root_defaults['gray-color'] . '-50';
			$root_defaults['svg-filter']               = 'invert(100%)';
		} elseif ( wpgen_options( 'general_color_scheme' ) === 'light' ) {
			$root_defaults['primary-bg-color']         = $root_defaults['gray-color'] . '-200';
			$root_defaults['primary-bg-color-hover']   = $root_defaults['gray-color'] . '-300';
			$root_defaults['primary-bd-color']         = $root_defaults['gray-color'] . '-300';
			$root_defaults['primary-bd-color-hover']   = $root_defaults['gray-color'] . '-400';
			$root_defaults['primary-gray-color']       = $root_defaults['gray-color'] . '-500';
			$root_defaults['primary-gray-color-hover'] = $root_defaults['gray-color'] . '-600';
			$root_defaults['primary-text-color']       = $root_defaults['gray-color'] . '-950';
			$root_defaults['white-color']              = $root_defaults['gray-color'] . '-50';
			$root_defaults['svg-filter']               = 'invert(0%)';
		} else {
			$root_defaults['primary-bg-color']         = $root_defaults['gray-color'] . '-50';
			$root_defaults['primary-bg-color-hover']   = $root_defaults['gray-color'] . '-200';
			$root_defaults['primary-bd-color']         = $root_defaults['gray-color'] . '-300';
			$root_defaults['primary-bd-color-hover']   = $root_defaults['gray-color'] . '-400';
			$root_defaults['primary-gray-color']       = $root_defaults['gray-color'] . '-500';
			$root_defaults['primary-gray-color-hover'] = $root_defaults['gray-color'] . '-600';
			$root_defaults['primary-text-color']       = $root_defaults['gray-color'] . '-950';
			$root_defaults['white-color']              = $root_defaults['gray-color'] . '-50';
			$root_defaults['svg-filter']               = 'invert(0%)';
		}

		$root_defaults = apply_filters( 'get_root_defaults', $root_defaults );

		// Return controls.
		if ( is_null( $control ) ) {
			return $root_defaults;
		} elseif ( ! isset( $root_defaults[ $control ] ) || empty( $root_defaults[ $control ] ) ) {
			return false;
		} else {
			return $root_defaults[ $control ];
		}
	}
}

/*// Usage: change root default gray.
add_filter( 'get_root_default_gray', 'change_get_root_default_gray', 15 );
if ( ! function_exists( 'change_get_root_default_gray' ) ) {
	function change_get_root_default_gray( $root_gray ) {
		return 'neutral';
	}
}*/

/*// Usage: change root defaults.
add_filter( 'get_root_defaults', 'change_root_defaults', 15 );
if ( ! function_exists( 'change_root_defaults' ) ) {
	function change_root_defaults( $root_styles ) {

		$source_styles = array(
			'primaryFont'     => 'jost',
			'secondaryFont'   => 'jost',
			'primary-color'   => 'sky',
			'secondary-color' => 'orange',
		);

		if ( wpgen_options( 'general_color_scheme' ) === 'black' ) {
			$source_styles['elemBgColor']      = $root_styles['primary-bg-color'];
			$source_styles['elemBdColor']      = $root_styles['primary-bd-color'];
			$source_styles['elemBdColorHover'] = $root_styles['primary-bd-color-hover'];
			$source_styles['elemTextColor']    = $root_styles['primary-text-color'];
		} elseif ( wpgen_options( 'general_color_scheme' ) === 'dark' ) {
			$source_styles['elemBgColor']      = $root_styles['primary-bg-color'];
			$source_styles['elemBdColor']      = $root_styles['primary-bd-color'];
			$source_styles['elemBdColorHover'] = $root_styles['primary-bd-color-hover'];
			$source_styles['elemTextColor']    = $root_styles['primary-text-color'];
		} elseif ( wpgen_options( 'general_color_scheme' ) === 'light' ) {
			$source_styles['elemBgColor']      = $root_styles['primary-bg-color'];
			$source_styles['elemBdColor']      = $root_styles['primary-bd-color'];
			$source_styles['elemBdColorHover'] = $root_styles['primary-bd-color-hover'];
			$source_styles['elemTextColor']    = $root_styles['primary-text-color'];
		} else {
			$source_styles['elemBgColor']      = $root_styles['primary-bg-color'];
			$source_styles['elemBdColor']      = $root_styles['primary-bd-color'];
			$source_styles['elemBdColorHover'] = $root_styles['primary-bd-color-hover'];
			$source_styles['elemTextColor']    = $root_styles['primary-text-color'];
		}

		return wp_parse_args( $source_styles, $root_styles );
	}
}*/
