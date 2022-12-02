<?php
/**
 * Wpgen main functions
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_style_by_saturate' ) ) {

	/**
	 * Get theme style by saturation.
	 *
	 * @param int $saturation color saturation to get theme style.
	 *
	 * @return string
	 */
	function get_style_by_saturate( $saturation = null ) {

		if ( is_null( $saturation ) ) {
			return false;
		}

		if ( in_array( (int) $saturation, array( 800, 900 ), true ) ) {
			$theme_style = 'black';
		} elseif ( in_array( (int) $saturation, array( 500, 600, 700 ), true ) ) {
			$theme_style = 'dark';
		} elseif ( in_array( (int) $saturation, array( 200, 300, 400 ), true ) ) {
			$theme_style = 'light';
		} else {
			$theme_style = 'white';
		}

		return $theme_style;
	}
}

if ( ! function_exists( 'get_color_style_by_saturate' ) ) {

	/**
	 * Get hard theme style by saturation.
	 *
	 * @param int $saturation color saturation to get theme color.
	 *
	 * @return string
	 */
	function get_color_style_by_saturate( $saturation = null ) {

		if ( is_null( $saturation ) ) {
			return false;
		}

		if ( in_array( (int) $saturation, array( 500, 600, 700, 800, 900 ), true ) ) {
			$theme_style = 'black';
		} else {
			$theme_style = 'white';
		}

		return $theme_style;
	}
}

if ( ! function_exists( 'get_opposite_color_style_by_saturate' ) ) {

	/**
	 * Get opposite theme color by saturation.
	 *
	 * @param int $saturation color saturation to get theme color.
	 *
	 * @return string
	 */
	function get_opposite_color_style_by_saturate( $saturation = null ) {

		if ( is_null( $saturation ) ) {
			return false;
		}

		if ( in_array( (int) $saturation, array( 500, 600, 700, 800, 900 ), true ) ) {
			$theme_style = 'white';
		} else {
			$theme_style = 'black';
		}

		return $theme_style;
	}
}

if ( ! function_exists( 'get_next_saturate' ) ) {

	/**
	 * Get next saturate by saturation.
	 *
	 * @param int $saturation color saturation to get next.
	 *
	 * @return int
	 */
	function get_next_saturate( $saturation = null ) {

		if ( is_null( $saturation ) ) {
			return false;
		}

		if ( (int) $saturation === 50 ) {
			$value = (int) $saturation + 50;
		} elseif ( (int) $saturation === 900 ) {
			$value = (int) $saturation - 100;
		} else {
			$value = (int) $saturation + 100;
		}

		return $value;
	}
}

if ( ! function_exists( 'get_prev_saturate' ) ) {

	/**
	 * Get prev saturate by saturation.
	 *
	 * @param int $saturation color saturation to get prev.
	 *
	 * @return int
	 */
	function get_prev_saturate( $saturation = null ) {

		if ( is_null( $saturation ) ) {
			return false;
		}

		if ( (int) $saturation === 100 ) {
			$value = (int) $saturation - 50;
		} elseif ( (int) $saturation === 50 ) {
			$value = (int) $saturation + 50;
		} else {
			$value = (int) $saturation - 100;
		}

		return $value;
	}
}
