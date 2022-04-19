<?php
/**
 * defaults root styles
 *
 * @package wpgen
 */

if ( !defined( 'ABSPATH' ) )
	exit;

// функция возвращает массив с root стилями по умолчанию
if ( !function_exists( 'get_root_defaults' ) ) {
	function get_root_defaults( $control = null ) {

		$root_defaults = [
			'primaryFont' => 'montserrat',
			'secondaryFont' => 'montserrat',

			'primaryColorDark' => 'sky-700',
			'primaryColorDarken' => 'sky-600',
			'primaryColor' => 'sky-500',
			'primaryColorLighten' => 'sky-400',
			'primaryColorLight' => 'sky-300',
			
			'secondaryColorDark' => 'orange-700',
			'secondaryColorDarken' => 'orange-600',
			'secondaryColor' => 'orange-500',
			'secondaryColorLighten' => 'orange-400',
			'secondaryColorLight' => 'orange-300',

			'grayColorDark' => 'slate-700',
			'grayColorDarken' => 'slate-600',
			'grayColor' => 'slate-500',
			'grayColorLighten' => 'slate-400',
			'grayColorLight' => 'slate-300',

			'bgColorDark' => 'slate-900',
			'bgColorDarken' => 'slate-800',
			'bgColorLighten' => 'slate-300',
			'bgColorLight' => 'slate-200',

			'whiteColor' => 'slate-50',
			'textColor' => 'slate-900',

			'linkColorDark' => 'orange-600',
			'linkColor' => 'orange-500',
			'linkColorLight' => 'orange-400',

			'allertColor' => '#F9423A',
			'warningColor' => '#F3EA5D',
			'acceptColor' => '#79D97C',

			'elemBgColor' => 'slate-200',
			'elemTextColor' => 'slate-900',
			'elemPadding' => 'p-3',
			'elemShadow' => 'shadow-md',
			'elemShadowHover' => 'shadow-lg',
			'elemBdColor' => 'sky-300',
			'elemBdColorHover' => 'sky-400',
			'elemBdWidth' => 'border-2',
			'elemBdRadius' => 'rounded-md',

			'btnSize' => 'btn-lg',
			'btnBdRadius' => 'rounded-md',

			'inputBdColor' => 'slate-300',
		];

		$root_defaults = apply_filters( 'root_defaults_filter_options', $root_defaults );

		//	Return controls
		if ( $control == null ) {
			return $root_defaults;
		} else {
			return $root_defaults[$control];
		}

	}
}

/*add_filter( 'root_defaults_filter_options', 'source_root_defaults_filter_options', 30 );
function source_root_defaults_filter_options( $root_styles ) {

	$source_styles = [
		'primaryFont' => 'jost',
		'secondaryFont' => 'jost',
	];

	return wp_parse_args( $source_styles, $root_styles );

}*/

// функция возвращает массив с root стилями (для тестов и принта в wp_add_inline_style)
if ( !function_exists( 'get_root_styles' ) ) {
	function get_root_styles( $control = null ) {

		$root_styles = array();
		$root_defaults = get_root_defaults();

		foreach ( $root_defaults as $key => $root_default ) {

			if ( in_array( $key, ['primaryFont', 'secondaryFont'] ) ) {
				$root_styles[$key] = get_selected_font( $root_default );
			} elseif( $key === 'btnSize' ) {
				$root_styles['buttonPaddingTop'] = explode( ' ', get_selected_value( $root_default ) )[0];
				$root_styles['buttonPaddingLeft'] = explode( ' ', get_selected_value( $root_default ) )[1];
			} else {
				$root_styles[$key] = get_selected_value( $root_default );
			}
			
		}

		//	Merge child and parent default options
		$root_styles = apply_filters( 'root_styles_filter_options', $root_styles );

		//	Merge defaults and wpgen options
		$root_styles = wp_parse_args( get_wpgen_root_style(), $root_styles );

		//	Return controls
		if ( $control == null ) {
			return $root_styles;
		} else {
			return $root_styles[$control];
		}

	}
}


/*add_filter( 'root_styles_filter_options', 'source_root_styles_filter_options', 30 );
function source_root_styles_filter_options( $root_styles ) {

	$source_styles = [
		'primaryFont' => '\'Jost\'',
		'secondaryFont' => '\'Jost\'',
	];

	return wp_parse_args( $source_styles, $root_styles );

}*/