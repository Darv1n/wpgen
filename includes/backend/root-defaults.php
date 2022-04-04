<?php
/**
 * defaults root styles
 *
 * @package wpgen
 */

if ( !defined( 'ABSPATH' ) )
	exit;

// функция возвращает массив с root стилями (для тестов и принта в wp_add_inline_style)
if ( !function_exists( 'get_root_style' ) ) {
	function get_root_style( $control = null ) {

		$root_defaults = [
			'primaryFont' => '\'Montserrat\'',
			'secondaryFont' => '\'Montserrat\'',

			'buttonPaddingTop' => '.75rem', // btn
			'buttonPaddingLeft' => '1.75rem', // btn

			'primaryColorDark' => RGBtoHEX( 'rgb( 3, 105, 161 )' ), // sky-700
			'primaryColorDarken' => RGBtoHEX( 'rgb( 2, 132, 199 )' ), // sky-600
			'primaryColor' => RGBtoHEX( 'rgb( 14, 165, 233 )' ), // sky-500
			'primaryColorLighten' => RGBtoHEX( 'rgb( 56, 189, 248 )' ),  // sky-400
			'primaryColorLight' => RGBtoHEX( 'rgb( 125, 211, 252 )' ), // sky-300
			
			'secondaryColorDark' => RGBtoHEX( 'rgb( 194, 65, 12 )' ), // orange-700
			'secondaryColorDarken' => RGBtoHEX( 'rgb( 234, 88, 12 )' ), // orange-600
			'secondaryColor' => RGBtoHEX( 'rgb( 249, 115, 22 )' ), // orange-500
			'secondaryColorLighten' => RGBtoHEX( 'rgb( 251, 146, 60 )' ), // orange-400
			'secondaryColorLight' => RGBtoHEX( 'rgb( 253, 186, 116 )' ), // orange-300

			'grayColorDark' => RGBtoHEX( 'rgb( 51, 65, 85 )' ), // slate-700
			'grayColorDarken' => RGBtoHEX( 'rgb( 71, 85, 105 )' ), // slate-600
			'grayColor' => RGBtoHEX( 'rgb( 100, 116, 139 )' ), // slate-500
			'grayColorLighten' => RGBtoHEX( 'rgb( 148, 163, 184 )' ), // slate-400
			'grayColorLight' => RGBtoHEX( 'rgb( 203, 213, 225 )' ), // slate-300

			'bgColorDark' => RGBtoHEX( 'rgb( 15, 23, 42 )' ), // slate-900
			'bgColorDarken' => RGBtoHEX( 'rgb( 30, 41, 59 )' ), // slate-800
			'bgColorLighten' => RGBtoHEX( 'rgb( 203, 213, 225 )' ), // slate-300
			'bgColorLight' => RGBtoHEX( 'rgb( 226, 232, 240 )' ), // slate-200

			'whiteColor' => RGBtoHEX( 'rgb( 248, 250, 252 )' ), // slate-50
			'textColor' => RGBtoHEX( 'rgb( 15, 23, 42 )' ), // slate-900

			'linkColorDark' => RGBtoHEX( 'rgb( 234, 88, 12 )' ), // orange-600
			'linkColor' => RGBtoHEX( 'rgb( 249, 115, 22 )' ), // orange-500
			'linkColorLight' => RGBtoHEX( 'rgb( 251, 146, 60 )' ), // orange-400

			'allertColor' => '#F9423A',
			'warningColor' => '#F3EA5D',
			'acceptColor' => '#79D97C',

			'elemBgColor' => RGBtoHEX( 'rgb( 226, 232, 240 )' ), // slate-200
			'elemTextColor' => RGBtoHEX( 'rgb( 15, 23, 42 )' ), // slate-900
			'elemPadding' => '.75rem', // p-3
			'elemShadow' => '0 4px 6px -1px rgba( 0, 0, 0, 0.15 ), 0 2px 4px -2px rgba( 0, 0, 0, 0.15 )', // shadow-md
			'elemShadowHover' => '0 10px 15px -3px rgba( 0, 0, 0, 0.15 ), 0 4px 6px -4px rgba( 0, 0, 0, 0.15 )', // shadow-lg
			'elemBdColor' => RGBtoHEX( 'rgb( 125, 211, 252 )' ), // sky-300
			'elemBdColorHover' => RGBtoHEX( 'rgb( 56, 189, 248 )' ),  // sky-400
			'elemBdWidth' => '2px', // border-2
			'elemBdRadius' => '0.375rem', // rounded-md

			'inputBdColor' => RGBtoHEX( 'rgb( 203, 213, 225 )' ), // slate-300
		];

		//	Merge child and parent default options
		$root_defaults = apply_filters( 'root_filter_options', $root_defaults );

		//	Merge defaults and wpgen options
		$root_defaults = wp_parse_args( get_wpgen_root_style(), $root_defaults );

		//	Return controls
		if ( $control == null ) {
			return $root_defaults;
		} else {
			return $root_defaults[$control];
		}

	}
}


/*add_filter( 'root_filter_options','source_root_filter_options', 30 );
function source_root_filter_options( $root_defaults ) {

	$source_defaults = [
		'primaryFont' => '\'Jost\'',
		'secondaryFont' => '\'Jost\'',
	];

	return wp_parse_args( $source_defaults, $root_defaults );

}*/