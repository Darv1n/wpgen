<?php
/**
 * Wpgen main functions
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( !function_exists( 'get_style_by_saturate' ) ) {
	function get_style_by_saturate( $name = null ) {

		if ( $name === null ) {
			return false;
		}

		if ( in_array( $name, ['800', '900'] ) ) {
			$theme_style = 'black';
		} elseif ( in_array( $name, ['500', '600', '700'] ) ) {
			$theme_style = 'dark';
		} elseif ( in_array( $name, ['200', '300', '400'] ) ) {
			$theme_style = 'light';
		} else {
			$theme_style = 'white';
		}

		return $theme_style;

	}
}

if ( !function_exists( 'get_color_style_by_saturate' ) ) {
	function get_color_style_by_saturate( $name = null ) {

		if ( $name === null ) {
			return false;
		}

		if ( in_array( $name, [500, 600, 700, 800, 900] ) ) {
			$theme_style = 'black';
		} else {
			$theme_style = 'white';
		}

		return $theme_style;

	}
}

if ( !function_exists( 'get_opposite_color_style_by_saturate' ) ) {
	function get_opposite_color_style_by_saturate( $name = null ) {

		if ( $name === null ) {
			return false;
		}

		if ( in_array( $name, [500, 600, 700, 800, 900] ) ) {
			$theme_style = 'white';
		} else {
			$theme_style = 'black';
		}

		return $theme_style;

	}
}


if ( !function_exists( 'get_next_saturate' ) ) {
	function get_next_saturate( $saturate = null ) {

		if ( $saturate === null ) {
			return false;
		}

		if ( (int) $saturate == 50 ) {
			$value = (int) $saturate + 50;
		} elseif ( (int) $saturate == 900 ) {
			$value = (int) $saturate - 100;
		} else {
			$value = (int) $saturate + 100;
		}

		return $value;

	}
}

if ( !function_exists( 'get_prev_saturate' ) ) {
	function get_prev_saturate( $saturate = null ) {

		if ( $saturate === null ) {
			return false;
		}

		if ( (int) $saturate == 100 ) {
			$value = (int) $saturate - 50;
		} elseif ( (int) $saturate == 50 ) {
			$value = (int) $saturate + 50;
		} else {
			$value = (int) $saturate - 100;
		}

		return $value;

	}
}