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
			'white-color'     => 'slate-50',

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

		if ( wpgen_options( 'general_color_scheme' ) === 'black' ) {
			$root_defaults['primary-bg-color']         = 'slate-950';
			$root_defaults['primary-bg-color-hover']   = 'slate-900';
			$root_defaults['primary-bd-color']         = 'slate-800';
			$root_defaults['primary-bd-color-hover']   = 'slate-700';
			$root_defaults['primary-gray-color']       = 'slate-300';
			$root_defaults['primary-gray-color-hover'] = 'slate-400';
			$root_defaults['primary-text-color']       = 'slate-50';
			$root_defaults['svg-filter']               = 'invert(100%)';
		} elseif ( wpgen_options( 'general_color_scheme' ) === 'dark' ) {
			$root_defaults['primary-bg-color']         = 'slate-800';
			$root_defaults['primary-bg-color-hover']   = 'slate-900';
			$root_defaults['primary-bd-color']         = 'slate-900';
			$root_defaults['primary-bd-color-hover']   = 'slate-950';
			$root_defaults['primary-gray-color']       = 'slate-300';
			$root_defaults['primary-gray-color-hover'] = 'slate-400';
			$root_defaults['primary-text-color']       = 'slate-50';
			$root_defaults['svg-filter']               = 'invert(100%)';
		} elseif ( wpgen_options( 'general_color_scheme' ) === 'light' ) {
			$root_defaults['primary-bg-color']         = 'slate-200';
			$root_defaults['primary-bg-color-hover']   = 'slate-300';
			$root_defaults['primary-bd-color']         = 'slate-300';
			$root_defaults['primary-bd-color-hover']   = 'slate-400';
			$root_defaults['primary-gray-color']       = 'slate-500';
			$root_defaults['primary-gray-color-hover'] = 'slate-600';
			$root_defaults['primary-text-color']       = 'slate-950';
			$root_defaults['svg-filter']               = 'invert(0%)';
		} else {
			$root_defaults['primary-bg-color']         = 'slate-50';
			$root_defaults['primary-bg-color-hover']   = 'slate-200';
			$root_defaults['primary-bd-color']         = 'slate-300';
			$root_defaults['primary-bd-color-hover']   = 'slate-400';
			$root_defaults['primary-gray-color']       = 'slate-500';
			$root_defaults['primary-gray-color-hover'] = 'slate-600';
			$root_defaults['primary-text-color']       = 'slate-950';
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

/*// Usage: change root defaults.
add_filter( 'get_root_defaults', 'change_root_defaults', 15 );
if ( ! function_exists( 'change_root_defaults' ) ) {
	function change_root_defaults( $root_styles ) {

		$source_styles = array(
			'primaryFont'   => 'jost',
			'secondaryFont' => 'jost',
		);

		return wp_parse_args( $source_styles, $root_styles );
	}
}*/
