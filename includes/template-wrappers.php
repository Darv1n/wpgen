<?php
/**
 * Wpgen wrappers functions
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add custom classes to the array of body classes.
 *
 * @param string $classes body classes.
 *
 * @return array
 */
function wpgen_body_classes( $classes ) {

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( wpgen_options( 'sidebar_display' ) && ! wpgen_options( 'sidebar_left_display' ) && ! wpgen_options( 'sidebar_right_display' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Check if the site is being viewed from a mobile device.
	if ( wp_is_mobile() ) {
		$classes[] = 'wp-mobile';
	} else {
		$classes[] = 'wp-desktop';
	}

	// Adds class with themename.
	$classes[] = 'theme_' . wp_get_theme()->get( 'Name' );
	$classes[] = 'theme_' . wpgen_options( 'general_color_scheme' );
	$classes[] = 'theme_' . wpgen_options( 'general_container_width' );

	// Color background elements.
	$values                 = array_flip( get_selected_value() );
	$elem_bg_color          = get_root_styles( 'elemBgColor' );
	$elem_bg_color_value    = $values[ $elem_bg_color ];
	$elem_bg_color_saturate = preg_replace( '/\D+/', '', $elem_bg_color_value );
	$elem_bg_color_style    = get_style_by_saturate( $elem_bg_color_saturate );

	if ( $elem_bg_color_style ) {
		$classes[] = 'theme_elems_' . $elem_bg_color_style;
	}

	return array_unique( $classes );
}
add_filter( 'body_class', 'wpgen_body_classes' );


/**
 * Add custom classes to the array of post classes.
 *
 * @param string $classes post classes.
 *
 * @return array
 */
function wpgen_post_classes( $classes ) {

	// Collect array.
	$classes[] = 'entry';

	if ( ! in_array( 'article-single', $classes, true ) ) {
		$classes[] = 'article-archive';
	}

	// Adds type article classes for single.
	if ( is_single() ) {
		$classes[] = 'article-template-' . wpgen_options( 'single_post_template_type' );
	}

	// Adds type article classes for archive.
	if ( is_archive() ) {
		$classes[] = 'article-template_' . wpgen_options( 'archive_page_template_type' );
	}

	$classes = apply_filters( 'wpgen_post_classes', $classes );
	return array_unique( $classes );
}
add_filter( 'post_class', 'wpgen_post_classes' );



if ( ! function_exists( 'get_wpgen_container_classes' ) ) {

	/**
	 * Get classes for container wrapper.
	 *
	 * @param string $class container classes.
	 *
	 * @return array
	 */
	function get_wpgen_container_classes( $class = '' ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'container';
		$classes[] = 'container-' . wpgen_options( 'general_container_width' );

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_container_classes' ) ) {

	/**
	 * Display classes for container wrapper.
	 *
	 * @param string $class container classes.
	 * @param bool   $echo  echo or return container classes.
	 *
	 * @return string
	 */
	function wpgen_container_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_container_classes( $class );
		$classes = apply_filters( 'wpgen_container_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}




// Тестирование.
/*add_filter( 'wpgen_container_classes','my_class_names' );
function my_class_names( $classes ) {

	$classes[] = 'it_is_page';

	return $classes;
}*/


if ( ! function_exists( 'get_wpgen_content_area_classes' ) ) {

	/**
	 * Get classes for main content area wrapper.
	 *
	 * @param string $class content area classes.
	 *
	 * @return array
	 */
	function get_wpgen_content_area_classes( $class = '' ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'content-area';

		if ( is_404() ) {
			$classes[] = 'error-404 ';
			$classes[] = 'not-found';
		}

		$classes[] = 'col-12';
		$classes[] = 'order-1';
		$classes[] = 'col-sm-12';

		if ( is_author() ) {
			$classes[] = 'col-xl-10';
			$classes[] = 'offset-xl-1';
		} else {
			if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
				$classes[] = 'col-lg-6';
				$classes[] = 'order-lg-2';
			} elseif ( wpgen_options( 'sidebar_left_display' ) || wpgen_options( 'sidebar_right_display' ) ) {
				$classes[] = 'col-lg-8';
				$classes[] = 'order-lg-2';
				$classes[] = 'col-xl-9';
			} else {
				if ( wpgen_options( 'general_content_width' ) === 'narrow' ) {
					$classes[] = 'col-md-10';
					$classes[] = 'offset-md-1';
					$classes[] = 'col-lg-8';
					$classes[] = 'offset-lg-2';
				} else {
					$classes[] = 'col-md-12';
				}
			}
		}

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_content_area_classes' ) ) {

	/**
	 * Display classes for main content area wrapper.
	 *
	 * @param string $class content area classes.
	 * @param bool   $echo  echo or return content area classes.
	 *
	 * @return string
	 */
	function wpgen_content_area_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_content_area_classes( $class );
		$classes = apply_filters( 'wpgen_content_area_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_widget_area_classes' ) ) {

	/**
	 * Get classes for sidebar widget area wrapper.
	 *
	 * @param string $class widget area classes.
	 *
	 * @return array
	 */
	function get_wpgen_widget_area_classes( $class = '' ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'widget-area';
		$classes[] = 'col-12';
		$classes[] = 'col-sm-12';

		if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
			$classes[] = 'col-lg-3';
		} else {
			$classes[] = 'col-lg-4';
			$classes[] = 'col-xl-3';
		}

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_widget_area_classes' ) ) {

	/**
	 * Display classes for sidebar widget area wrapper.
	 *
	 * @param string $class widget area classes.
	 * @param bool   $echo  echo or return widget area classes.
	 *
	 * @return string
	 */
	function wpgen_widget_area_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_widget_area_classes( $class );
		$classes = apply_filters( 'wpgen_widget_area_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_header_classes' ) ) {

	/**
	 * Get classes for header container.
	 *
	 * @param string $class header container classes.
	 *
	 * @return array
	 */
	function get_wpgen_header_classes( $class = '' ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'site__header';
		$classes[] = 'header';

		if ( has_custom_header() ) {
			$classes[] = 'header_background-image';
		}

		if ( wpgen_options( 'general_top_bar_display' ) ) {
			$classes[] = 'header_top-bar-active';
		}

		if ( wpgen_options( 'general_header_type' ) === 'header-content' ) {
			$classes[] = 'header_content';
		}

		if ( wpgen_options( 'general_header_type' ) === 'header-logo-center' ) {
			$classes[] = 'header_logo-center';
		}

		if ( wpgen_options( 'general_header_type' ) === 'header-simple' ) {
			$classes[] = 'header_simple';
		}

		$classes[] = 'header_' . wpgen_options( 'general_header_color_scheme' );
		$classes[] = 'header_menu_' . wpgen_options( 'general_menu_position' );

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_header_classes' ) ) {

	/**
	 * Display classes for header container.
	 *
	 * @param string $class header container classes.
	 * @param bool   $echo  echo or return header container classes.
	 *
	 * @return string
	 */
	function wpgen_header_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_header_classes( $class );
		$classes = apply_filters( 'wpgen_header_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_footer_classes' ) ) {

	/**
	 * Get classes for footer container.
	 *
	 * @param string $class footer container classes.
	 *
	 * @return array
	 */
	function get_wpgen_footer_classes( $class = '' ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'site__footer';
		$classes[] = 'footer';

		if ( wpgen_options( 'general_bottom_bar_display' ) ) {
			$classes[] = 'footer_bottom-bar-active';
		}

		if ( wpgen_options( 'general_footer_type' ) === 'footer-simple' ) {
			$classes[] = 'footer_simple';
		}
		if ( wpgen_options( 'general_footer_type' ) === 'footer-three-columns' ) {
			$classes[] = 'footer_three-columns';
		}
		if ( wpgen_options( 'general_footer_type' ) === 'footer-four-columns' ) {
			$classes[] = 'footer_four-columns';
		}

		$classes[] = 'footer_' . wpgen_options( 'general_color_scheme' );

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_footer_classes' ) ) {

	/**
	 * Display classes for footer container.
	 *
	 * @param string $class footer container classes.
	 * @param bool   $echo  echo or return footer container classes.
	 *
	 * @return string
	 */
	function wpgen_footer_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_footer_classes( $class );
		$classes = apply_filters( 'wpgen_footer_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_main_menu_classes' ) ) {

	/**
	 * Get classes for main menu.
	 *
	 * @param string $class main menu classes.
	 *
	 * @return array
	 */
	function get_wpgen_main_menu_classes( $class = '' ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'main-menu';

		if ( wpgen_options( 'general_menu_type' ) === 'menu-open' ) {
			$classes[] = 'main-menu_type-open';
		}
		if ( wpgen_options( 'general_menu_type' ) === 'menu-close' ) {
			$classes[] = 'main-menu_type-close';
		}

		$classes[] = 'main-menu-' . wpgen_options( 'general_menu_position' );
		if ( wpgen_options( 'general_menu_position' ) === 'absolute' ) {
			// эта костыльная хуйня, потому что при fixed css даются хедеру и там слишком сложные условия из-за этого класса, когда меню 'absolute'.
			$classes[] = 'main-menu-' . wpgen_options( 'general_menu_color_scheme' );
		}
		$classes[] = 'main-menu-' . wpgen_options( 'general_menu_align' );

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_main_menu_classes' ) ) {

	/**
	 * Display classes for main menu.
	 *
	 * @param string $class main menu classes.
	 * @param bool   $echo  echo or return main menu classes.
	 *
	 * @return string
	 */
	function wpgen_main_menu_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_main_menu_classes( $class );
		$classes = apply_filters( 'wpgen_main_menu_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_meta_display_classes' ) ) {

	/**
	 * Get classes for meta display single template TWO.
	 *
	 * @param string $class frontend meta description classes.
	 *
	 * @return array
	 */
	function get_wpgen_meta_display_classes( $class = '' ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'entry__meta';

		if ( wpgen_options( 'single_post_template_type' ) === 'one' ) {
			$classes[] = 'entry__meta_inline';
		}
		if ( wpgen_options( 'single_post_template_type' ) === 'two' ) {
			$classes[] = 'entry__meta_list';
		}

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_meta_display_classes' ) ) {

	/**
	 * Display classes for meta display single template TWO.
	 *
	 * @param string $class classes for meta display single template TWO.
	 * @param bool   $echo  echo or return classes for meta display single template TWO.
	 *
	 * @return string
	 */
	function wpgen_meta_display_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_meta_display_classes( $class );
		$classes = apply_filters( 'wpgen_meta_display_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_index_page_columns_classes' ) ) {

	/**
	 * Get classes for index page columns.
	 *
	 * @param string $class index page columns classes.
	 * @param string $columns_count return classes with specified columns.
	 *
	 * @return array
	 */
	function get_wpgen_index_page_columns_classes( $class = '', $columns_count = null ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'article-column';
		$classes[] = 'col-12';

		if ( is_null( $columns_count ) ) {
			$columns_count = wpgen_options( 'archive_page_columns' );
		}

		if ( intval( $columns_count ) > 0 && intval( $columns_count ) < 7 && is_int( intval( $columns_count ) ) ) {
			$columns_count = get_wpgen_count_columns( $columns_count, false );
		}

		// Default else is three columns.
		if ( $columns_count === 'six' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-3';
			$classes[] = 'col-xl-2';
			$classes[] = 'article-column-6';
		} elseif ( $columns_count === 'five' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-3';
			$classes[] = 'col-xl-5th';
			$classes[] = 'article-column-5';
		} elseif ( $columns_count === 'four' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-4';
			$classes[] = 'col-xl-3';
			$classes[] = 'article-column-4';
		} elseif ( $columns_count === 'two' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'article-column-2';
		} elseif ( $columns_count === 'one' ) {
			$classes[] = 'col-sm-12';
			$classes[] = 'article-column-1';
		} else {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-4';
			$classes[] = 'article-column-3';
		}

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_index_page_columns_classes' ) ) {

	/**
	 * Display classes for index page columns.
	 *
	 * @param string $class index page columns classes.
	 * @param bool   $echo  echo or return index page columns classes.
	 *
	 * @return string
	 */
	function wpgen_index_page_columns_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_index_page_columns_classes( $class );
		$classes = apply_filters( 'wpgen_index_page_columns_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_count_columns' ) ) {

	/**
	 * Get int count archive page columns.
	 *
	 * @param string $control text count columns.
	 *
	 * @return array
	 */
	function get_wpgen_count_columns( $control = null, $int = true ) {

		if ( is_null( $control ) ) {
			$control = wpgen_options( 'archive_page_columns' );
		}

		$converter = array(
			'one'   => 1,
			'two'   => 2,
			'three' => 3,
			'four'  => 4,
			'five'  => 5,
			'six'   => 6,
			'seven' => 7,
			'eight' => 8,
			'nine'  => 9,
			'ten'   => 10,
		);

		if ( array_key_exists( $control, $converter ) || $int ) {
			return strtr( $control, $converter );
		} else {
			return strtr( intval( $control ), array_flip( $converter ) );
		}
	}
}



if ( ! function_exists( 'get_wpgen_archive_page_columns_classes' ) ) {

	/**
	 * Get classes for archive page columns.
	 *
	 * @param string $class         archive page columns classes.
	 * @param string $columns_count return classes with specified columns.
	 *
	 * @return array
	 */
	function get_wpgen_archive_page_columns_classes( $class = '', $columns_count = null ) {

		$classes   = array();
		$classes[] = 'article-column';
		$classes[] = 'col-12';

		if ( is_null( $columns_count ) ) {
			$columns_count = wpgen_options( 'archive_page_columns' );
		}

		if ( intval( $columns_count ) > 0 && intval( $columns_count ) < 7 && is_int( intval( $columns_count ) ) ) {
			$columns_count = get_wpgen_count_columns( $columns_count, false );
		}

		// Default else is three columns.
		if ( $columns_count === 'six' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-3';
			$classes[] = 'col-xl-2';
			$classes[] = 'article-column-6';
		} elseif ( $columns_count === 'five' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-3';
			$classes[] = 'col-xl-5th';
			$classes[] = 'article-column-5';
		} elseif ( $columns_count === 'four' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-4';
			$classes[] = 'col-xl-3';
			$classes[] = 'article-column-4';
		} elseif ( $columns_count === 'two' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'article-column-2';
		} elseif ( $columns_count === 'one' ) {
			$classes[] = 'col-sm-12';
			$classes[] = 'article-column-1';
		} else {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-4';
			$classes[] = 'article-column-3';
		}

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_archive_page_columns_classes' ) ) {

	/**
	 * Display classes for archive page columns.
	 *
	 * @param string $class archive page columns classes.
	 * @param bool   $echo  echo or return archive page columns classes.
	 *
	 * @return string
	 */
	function wpgen_archive_page_columns_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_archive_page_columns_classes( $class );
		$classes = apply_filters( 'wpgen_archive_page_columns_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_archive_page_columns_wrapper_classes' ) ) {

	/**
	 * Get classes for archive page wrapper columns.
	 *
	 * @param string $class archive page column wrappers classes.
	 *
	 * @return array
	 */
	function get_wpgen_archive_page_columns_wrapper_classes( $class = '' ) {

		$classes   = array();
		$classes[] = 'row';

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_archive_page_columns_wrapper_classes' ) ) {

	/**
	 * Display classes for archive page wrapper columns.
	 *
	 * @param string $class archive page column wrappers classes.
	 * @param bool   $echo  echo or return archive page column wrappers classes.
	 *
	 * @return string
	 */
	function wpgen_archive_page_columns_wrapper_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_archive_page_columns_wrapper_classes( $class );
		$classes = apply_filters( 'wpgen_archive_page_columns_wrapper_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_button_classes' ) ) {

	/**
	 * Get classes for buttons.
	 *
	 * @param string $class button classes.
	 * @param string $color parameter is given by the wpgen arguments.
	 *
	 * @return array
	 */
	function get_button_classes( $class = '', $color = 'primary' ) {

		// Collect array.
		$classes   = array();
		$classes[] = 'button';

		if ( wpgen_options( 'general_button_type' ) === 'common' ) {
			$classes[] = 'button-' . $color;
		} elseif ( wpgen_options( 'general_button_type' ) === 'empty' ) {
			$classes[] = 'button-' . wpgen_options( 'general_button_type' );
		} else {
			$classes[] = 'button-' . wpgen_options( 'general_button_type' );

			if ( $color !== null ) {
				$classes[] = 'button-' . wpgen_options( 'general_button_type' ) . '-' . $color;
			}
		}

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'button_classes' ) ) {

	/**
	 * Display classes for buttons.
	 *
	 * @param string $class button classes.
	 * @param string $color parameter is given by the wpgen arguments (primary, secondary, gray, default).
	 * @param bool   $echo  echo or return button classes.
	 *
	 * @return string
	 */
	function button_classes( $class = '', $color = null, $echo = true ) {

		$classes = get_button_classes( $class, $color );
		$classes = apply_filters( 'button_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}



if ( ! function_exists( 'get_wpgen_link_more_classes' ) ) {

	/**
	 * Get link more classes.
	 *
	 * @param string $class link more classes.
	 * @param string $color parameter is given by the wpgen arguments.
	 *
	 * @return array
	 */
	function get_wpgen_link_more_classes( $class = '', $color = null ) {

		$classes = array();

		if ( wpgen_options( 'archive_page_detail_button' ) === 'button' ) {
			$classes[] = get_button_classes( '', $color );
			$classes[] = 'button_more';
		} else {
			$classes[] = 'link';
			$classes[] = 'link_more';
		}

		// Check whether the function has accepted any classes or not.
		if ( $class ) {
			$classes[] = $class;
		}

		return array_unique( $classes );
	}
}



if ( ! function_exists( 'wpgen_link_more_classes' ) ) {

	/**
	 * Display link more classes.
	 *
	 * @param string $class link more classes.
	 * @param bool   $echo  echo or return link more classes.
	 *
	 * @return string
	 */
	function wpgen_link_more_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_link_more_classes( $class );
		$classes = apply_filters( 'wpgen_link_more_classes', $classes, $class );
		$classes = array_unique( $classes );

		if ( $echo ) {
			echo 'class="' . implode( ' ', array_map( 'esc_attr', $classes ) ) . '"';
		} else {
			return array_map( 'esc_attr', $classes );
		}
	}
}
