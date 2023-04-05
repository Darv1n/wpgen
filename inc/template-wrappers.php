<?php
/**
 * Wpgen wrappers functions
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'body_class', 'wpgen_body_classes' );
if ( ! function_exists( 'wpgen_body_classes' ) ) {

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
		if ( ! wpgen_options( 'sidebar_left_display' ) && ! wpgen_options( 'sidebar_right_display' ) ) {
			$classes[] = 'no-sidebar';
		}

		// Check if the site is being viewed from a mobile device.
		if ( wp_is_mobile() ) {
			$classes[] = 'wp-mobile';
		} else {
			$classes[] = 'wp-desktop';
		}

		if ( is_404() ) {
			$classes[] = 'error-404';
		}

		if ( is_front_page() ) {
			$classes[] = 'front-page';
		}

		// Adds class with themename.
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

		return array_unique( (array) $classes );
	}
}

add_filter( 'post_class', 'wpgen_post_classes' );
if ( ! function_exists( 'wpgen_post_classes' ) ) {

	/**
	 * Add custom classes to the array of post classes.
	 *
	 * @param string $classes post classes.
	 *
	 * @return array
	 */
	function wpgen_post_classes( $classes ) {

		$format = get_post_format();
		if ( ! $format ) {
			$format = 'standard';
		}

		if ( in_array( 'hentry', $classes, true ) ) {
			unset( $classes[ array_search( 'hentry', $classes ) ] );
		}
		if ( in_array( 'sticky', $classes, true ) ) {
			unset( $classes[ array_search( 'sticky', $classes ) ] );
		}
		if ( in_array( 'post-' . get_the_ID(), $classes, true ) ) {
			unset( $classes[ array_search( 'post-' . get_the_ID(), $classes ) ] );
		}
		if ( in_array( 'type-' . get_post_type(), $classes, true ) ) {
			unset( $classes[ array_search( 'type-' . get_post_type(), $classes ) ] );
		}
		if ( in_array( get_post_type(), $classes, true ) ) {
			unset( $classes[ array_search( get_post_type(), $classes ) ] );
		}
		if ( in_array( 'status-' . get_post_status(), $classes, true ) ) {
			unset( $classes[ array_search( 'status-' . get_post_status(), $classes ) ] );
		}
		if ( in_array( 'format-' . $format, $classes, true ) ) {
			unset( $classes[ array_search( 'format-' . $format, $classes ) ] );
		}

		$taxonomy_names = get_object_taxonomies( get_post_type() );

		foreach ( $taxonomy_names as $key => $taxonomy ) {
			$terms = get_the_terms( get_the_ID(), $taxonomy );
			if ( $terms ) {
				foreach ( get_the_terms( get_the_ID(), $taxonomy ) as $key => $term ) {

					if ( $taxonomy === 'post_tag' ) {
						$taxonomy = 'tag';
					}

					if ( in_array( $taxonomy . '-' . $term->slug, $classes, true ) ) {
						unset( $classes[ array_search( $taxonomy . '-' . $term->slug, $classes ) ] );
					}
				}
			}
		}

		$classes[] = 'post';
		$classes[] = 'post_' . get_post_type();
		$classes[] = get_post_type();

		// Crutch to distinguish single post template from archive template
		if ( ! in_array( 'post_single', $classes, true ) ) {
			$classes[] = 'post_archive';
			$classes[] = 'post_' . wpgen_options( 'archive_' . get_post_type() . '_template_type' );
		}

		$classes = apply_filters( 'wpgen_post_classes', $classes );

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'get_wpgen_container_classes' ) ) {

	/**
	 * Get classes for container wrapper.
	 *
	 * @param string $class Additional container classes.
	 *
	 * @return array
	 */
	function get_wpgen_container_classes( $class = '' ) {

		// Add elements to array.
		$classes   = array();
		$classes[] = 'container';
		$classes[] = 'container-' . wpgen_options( 'general_container_width' );

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_container_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_container_classes', 'my_container_classes' );
		if ( ! function_exists( 'my_container_classes' ) ) {
			function my_container_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_container_classes' ) ) {

	/**
	 * Display classes for container wrapper.
	 *
	 * @param string $class Additional container classes.
	 * @param bool   $echo  Echo or return container classes.
	 *
	 * @return string
	 */
	function wpgen_container_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_container_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_content_area_classes' ) ) {

	/**
	 * Get classes for main content area wrapper.
	 *
	 * @param string $class Additional content area classes.
	 *
	 * @return array
	 */
	function get_wpgen_content_area_classes( $class = '' ) {

		// Add elements to array.
		$classes   = array();

		$classes[] = 'col-12';
		$classes[] = 'order-1';
		$classes[] = 'col-sm-12';

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

		$classes[] = 'content-area';

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_content_area_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_content_area_classes', 'my_content_area_classes' );
		if ( ! function_exists( 'my_content_area_classes' ) ) {
			function my_content_area_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_content_area_classes' ) ) {

	/**
	 * Display classes for main content area wrapper.
	 *
	 * @param string $class Additional content area classes.
	 * @param bool   $echo  Echo or return content area classes.
	 *
	 * @return string
	 */
	function wpgen_content_area_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_content_area_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_widget_area_classes' ) ) {

	/**
	 * Get classes for sidebar widget area wrapper.
	 *
	 * @param string $class Additional widget area classes.
	 *
	 * @return array
	 */
	function get_wpgen_widget_area_classes( $class = '' ) {

		// Add elements to array.
		$classes   = array();

		$classes[] = 'col-12';
		$classes[] = 'col-sm-12';

		if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
			$classes[] = 'col-lg-3';
		} else {
			$classes[] = 'col-lg-4';
			$classes[] = 'col-xl-3';
		}

		$classes[] = 'widget-area';

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_widget_area_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_widget_area_classes', 'my_widget_area_classes' );
		if ( ! function_exists( 'my_widget_area_classes' ) ) {
			function my_widget_area_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_widget_area_classes' ) ) {

	/**
	 * Display classes for sidebar widget area wrapper.
	 *
	 * @param string $class Additional widget area classes.
	 * @param bool   $echo  Echo or return widget area classes.
	 *
	 * @return string
	 */
	function wpgen_widget_area_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_widget_area_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_header_classes' ) ) {

	/**
	 * Get classes for header container.
	 *
	 * @param string $class Additional header classes.
	 *
	 * @return array
	 */
	function get_wpgen_header_classes( $class = '' ) {

		// Add elements to array.
		$classes   = array();
		$classes[] = 'header';

		if ( has_custom_header() ) {
			$classes[] = 'header_background-image';
		}

		if ( wpgen_options( 'general_header_top_bar_display' ) ) {
			$classes[] = 'header_top-bar-active';
		}

		if ( wpgen_options( 'general_header_type' ) === 'header-content' ) {
			$classes[] = 'header_content';
		} elseif ( wpgen_options( 'general_header_type' ) === 'header-logo-center' ) {
			$classes[] = 'header_logo-center';
		} elseif ( wpgen_options( 'general_header_type' ) === 'header-simple' ) {
			$classes[] = 'header_simple';
		}

		if ( is_admin_bar_showing() ) {
			$classes[] = 'is_wpadminbar';
		}

		$classes[] = 'header_menu_' . wpgen_options( 'general_menu_position' );

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_header_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_header_classes', 'my_header_classes' );
		if ( ! function_exists( 'my_header_classes' ) ) {
			function my_header_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_header_classes' ) ) {

	/**
	 * Display classes for header container.
	 *
	 * @param string $class Additional header classes.
	 * @param bool   $echo  Echo or return header classes.
	 *
	 * @return string
	 */
	function wpgen_header_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_header_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_footer_classes' ) ) {

	/**
	 * Get classes for footer container.
	 *
	 * @param string $class Additional footer classes.
	 *
	 * @return array
	 */
	function get_wpgen_footer_classes( $class = '' ) {

		// Add elements to array.
		$classes   = array();
		$classes[] = 'footer';

		if ( wpgen_options( 'general_footer_bottom_bar_display' ) ) {
			$classes[] = 'footer_bottom-bar-active';
		}

		if ( wpgen_options( 'general_footer_type' ) === 'footer-simple' ) {
			$classes[] = 'footer_simple';
		} elseif ( wpgen_options( 'general_footer_type' ) === 'footer-three-columns' ) {
			$classes[] = 'footer_three-columns';
		} elseif ( wpgen_options( 'general_footer_type' ) === 'footer-four-columns' ) {
			$classes[] = 'footer_four-columns';
		}

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_footer_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_footer_classes', 'my_footer_classes' );
		if ( ! function_exists( 'my_footer_classes' ) ) {
			function my_footer_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_footer_classes' ) ) {

	/**
	 * Display classes for footer container.
	 *
	 * @param string $class Additional footer classes.
	 * @param bool   $echo  Echo or return footer classes.
	 *
	 * @return string
	 */
	function wpgen_footer_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_footer_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_main_menu_classes' ) ) {

	/**
	 * Get classes for main menu.
	 *
	 * @param string $class Additional main menu classes.
	 *
	 * @return array
	 */
	function get_wpgen_main_menu_classes( $class = '' ) {

		// Add elements to array.
		$classes   = array();
		$classes[] = 'main-menu';

		if ( wpgen_options( 'general_menu_type' ) === 'menu-open' ) {
			$classes[] = 'main-menu_type-open';
		} elseif ( wpgen_options( 'general_menu_type' ) === 'menu-close' ) {
			$classes[] = 'main-menu_type-close';
		}

		if ( wpgen_options( 'general_header_type' ) === 'header-simple' ) {
			$classes[] = 'main-menu_right';
		} else {
			$classes[] = 'main-menu_' . wpgen_options( 'general_menu_align' );
		}

		$classes[] = 'main-menu_' . wpgen_options( 'general_menu_position' );

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_main_menu_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_main_menu_classes', 'my_main_menu_classes' );
		if ( ! function_exists( 'my_main_menu_classes' ) ) {
			function my_main_menu_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_main_menu_classes' ) ) {

	/**
	 * Display classes for main menu.
	 *
	 * @param string $class Additional main menu classes.
	 * @param bool   $echo  Echo or return main menu classes.
	 *
	 * @return string
	 */
	function wpgen_main_menu_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_main_menu_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_meta_display_classes' ) ) {

	/**
	 * Get classes for meta display single template TWO.
	 *
	 * @param string $class Additional meta display classes.
	 *
	 * @return array
	 */
	function get_wpgen_meta_display_classes( $class = '' ) {

		// Add elements to array.
		$classes   = array();
		$classes[] = 'post__part';
		$classes[] = 'post__meta';
		$classes[] = 'post-meta';

		if ( wpgen_options( 'single_' . get_post_type() . '_template_type' ) === 'one' ) {
			$classes[] = 'post-meta_inline';
		} elseif ( wpgen_options( 'single_' . get_post_type() . '_template_type' ) === 'two' ) {
			$classes[] = 'post-meta_block';
		}

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_meta_display_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_meta_display_classes', 'my_meta_display_classes' );
		if ( ! function_exists( 'my_meta_display_classes' ) ) {
			function my_meta_display_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_meta_display_classes' ) ) {

	/**
	 * Display classes for meta display single template TWO.
	 *
	 * @param array  $args   Array with params for function:
	 *        string $class Additional meta display classes.
	 *        bool   $echo  Echo or return meta display classes.
	 *
	 * @return string
	 */
	function wpgen_meta_display_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_meta_display_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_archive_page_columns_wrapper_classes' ) ) {

	/**
	 * Get classes for archive page wrapper columns.
	 *
	 * @param string $class Additional archive page columns wrapper classes.
	 *
	 * @return array
	 */
	function get_wpgen_archive_page_columns_wrapper_classes( $class = '' ) {

		$classes   = array();
		$classes[] = 'row';
		$classes[] = 'row-' . get_wpgen_count_columns( wpgen_options( 'archive_post_columns' ) ) . '-col';

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_archive_page_columns_wrapper_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_archive_page_columns_wrapper_classes', 'my_archive_page_columns_wrapper_classes' );
		if ( ! function_exists( 'my_archive_page_columns_wrapper_classes' ) ) {
			function my_archive_page_columns_wrapper_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_archive_page_columns_wrapper_classes' ) ) {

	/**
	 * Display classes for archive page wrapper columns.
	 *
	 * @param string $class Additional archive page columns wrapper classes.
	 * @param bool   $echo  Echo or return archive page columns wrapper classes.
	 *
	 * @return string
	 */
	function wpgen_archive_page_columns_wrapper_classes( $class = '', $echo = true ) {

		$classes = get_wpgen_archive_page_columns_wrapper_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_count_columns' ) ) {

	/**
	 * Get int count archive page columns.
	 *
	 * @param string $control Text count columns.
	 * @param bool   $int     Need return int.
	 *
	 * @return array
	 */
	function get_wpgen_count_columns( $control = null, $int = true ) {

		if ( is_null( $control ) || empty( $control ) ) {
			$control = wpgen_options( 'archive_' . get_post_type() . '_columns' );
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
	 * @param int    $counter       Сolumn counter in loop.
	 * @param string $class         Additional archive page columns classes.
	 * @param string $columns_count Return classes with specified columns.
	 *
	 * @return array
	 */
	function get_wpgen_archive_page_columns_classes( $counter = null, $class = '', $columns_count = null ) {

		$classes   = array();
		$classes[] = 'col-12';

		if ( ! isset( $columns_count ) || is_null( $columns_count ) || empty( $columns_count ) ) {
			if ( get_post_type() ) {
				$post_type = get_post_type();
			} else {
				$post_type = 'post';
			}
			$columns_count = wpgen_options( 'archive_' . $post_type . '_columns' );
		}

		if ( is_int( intval( $columns_count ) ) && intval( $columns_count ) > 0 && intval( $columns_count ) <= 6 ) {
			$columns_count = get_wpgen_count_columns( $columns_count, false );
		}

		// Default else is three columns.
		if ( $columns_count === 'six' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-3';
			$classes[] = 'col-xl-2';
		} elseif ( $columns_count === 'five' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-3';
			$classes[] = 'col-xl-5th';
		} elseif ( $columns_count === 'four' ) {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-4';
			$classes[] = 'col-xl-3';
		} elseif ( $columns_count === 'two' ) {
			$classes[] = 'col-sm-6';
		} elseif ( $columns_count === 'one' ) {
			$classes[] = 'col-sm-12';
		} else {
			$classes[] = 'col-sm-6';
			$classes[] = 'col-lg-4';
		}

		$classes[] = 'post-col';
		if ( is_int( $counter ) ) {
			// $classes[] = 'post-col-' . $counter;
		}

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		// vardump( $counter );

		$classes = apply_filters( 'get_wpgen_archive_page_columns_classes', $classes, $counter );

		// Usage:
		/*add_filter( 'get_wpgen_archive_page_columns_classes', 'my_archive_page_columns_classes', 10, 2 );
		if ( ! function_exists( 'my_archive_page_columns_classes' ) ) {
			function my_archive_page_columns_classes( $classes, $counter ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_archive_page_columns_classes' ) ) {

	/**
	 * Display classes for archive page columns.
	 *
	 * @param int    $counter       Сolumn counter in loop.
	 * @param string $class         Additional archive page columns classes.
	 * @param string $columns_count Return classes with specified columns.
	 * @param bool   $echo          Echo or return archive page columns classes.
	 *
	 * @return string
	 */
	function wpgen_archive_page_columns_classes( $counter = null, $class = '', $columns_count = null, $echo = true ) {

		$classes = get_wpgen_archive_page_columns_classes( $counter, $class, $columns_count );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_button_classes' ) ) {

	/**
	 * Get classes for buttons.
	 *
	 * @param string $class Button classes.
	 * @param string $color Button color (primary, secondary, gray, default). Default 'primary'.
	 *
	 * @return array
	 */
	function get_button_classes( $class = '', $color = 'primary' ) {

		$color_scheme = wpgen_options( 'general_color_scheme' );
		$button_type  = wpgen_options( 'general_button_type' );

		// Add elements to array.
		$classes   = array();
		$classes[] = 'button';

		if ( $button_type === 'common' ) {
			$classes[] = 'button-' . $color;
		} else {
			$classes[] = 'button-' . $button_type;
			$classes[] = 'button-' . $button_type . '-' . $color;
		}

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		if ( in_array( 'icon', $classes, true ) ) {

			if ( ! in_array( 'icon_center', $classes, true ) ) {
				$classes[] = 'icon_' . wpgen_options( 'general_button_icon_position' );
			}

			if ( in_array( $color_scheme, array( 'white', 'light' ), true ) && ( $button_type === 'empty' || ( $button_type === 'common' && in_array( $color, array( 'gray', 'default' ), true ) ) ) ) {
				$classes[] = 'icon_black';
			} else {
				$classes[] = 'icon_white';
			}
		}

		// Удаляем все классы icon, если в опции стоит запрет.
		if ( ! wpgen_options( 'general_button_icon' ) ) {
			foreach ( $classes as $key => $class ) {
				if ( stripos( $class, 'icon' ) !== false ) {
					unset( $classes[ $key ] );
				}
			}
		}

		$classes = apply_filters( 'get_button_classes', $classes );

		// Usage:
		/*add_filter( 'get_button_classes', 'my_button_classes' );
		if ( ! function_exists( 'my_button_classes' ) ) {
			function my_button_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'button_classes' ) ) {

	/**
	 * Display classes for buttons.
	 *
	 * @param string $class Additional button classes.
	 * @param string $color Button color (primary, secondary, gray, default). Default 'primary'.
	 * @param bool   $echo  Echo or return button classes.
	 *
	 * @return string
	 */
	function button_classes( $class = '', $color = 'primary', $echo = true ) {

		$classes = get_button_classes( $class, $color );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_link_classes' ) ) {

	/**
	 * Get classes for links.
	 *
	 * @param string $class Link classes.
	 *
	 * @return array
	 */
	function get_link_classes( $class = '' ) {

		// Add elements to array.
		$classes   = array();
		$classes[] = 'link';
		$classes[] = 'link-color-unborder';

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_link_classes', $classes );

		// Usage:
		/*add_filter( 'get_link_classes', 'my_link_classes' );
		if ( ! function_exists( 'my_link_classes' ) ) {
			function my_link_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'link_classes' ) ) {

	/**
	 * Display classes for links.
	 *
	 * @param string $class Additional link classes.
	 * @param bool   $echo  Echo or return container classes.
	 *
	 * @return string
	 */
	function link_classes( $class = '', $echo = true ) {

		$classes = get_link_classes( $class );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}

if ( ! function_exists( 'get_wpgen_link_more_classes' ) ) {

	/**
	 * Get link more classes.
	 *
	 * @param string $class Link more classes.
	 * @param string $color Link more color (primary, secondary, gray, default). Default 'primary'.
	 *
	 * @return array
	 */
	function get_wpgen_link_more_classes( $class = '', $color = 'primary' ) {

		$classes = array();

		if ( get_post_type() ) {
			$post_type = get_post_type();
		} else {
			$post_type = 'post';
		}

		if ( wpgen_options( 'archive_' . $post_type . '_detail_button' ) === 'button' ) {
			$classes[] = 'icon';
			$classes[] = 'icon_arrow-right';
			$classes   = get_button_classes( $classes, $color );
		} else {
			$classes[] = 'link_more';
			$classes   = get_link_classes( $classes );
		}

		// Check the function has accepted any classes.
		if ( isset( $class ) && ! empty( $class ) ) {
			if ( is_array( $class ) ) {
				$classes = array_merge( $classes, $class );
			} elseif ( is_string( $class ) ) {
				$classes = array_merge( $classes, explode( ' ', $class ) );
			}
		}

		$classes = apply_filters( 'get_wpgen_link_more_classes', $classes );

		// Usage:
		/*add_filter( 'get_wpgen_link_more_classes', 'my_link_more_classes' );
		if ( ! function_exists( 'my_link_more_classes' ) ) {
			function my_link_more_classes( $classes ) {
				$classes[] = 'my-class';
				return array_unique( (array) $classes );
			}
		}*/

		return array_unique( (array) $classes );
	}
}

if ( ! function_exists( 'wpgen_link_more_classes' ) ) {

	/**
	 * Display link more classes.
	 *
	 * @param string $class Additional link more classes.
	 * @param string $color Link more color (primary, secondary, gray, default). Default 'primary'.
	 * @param bool   $echo  Echo or return link more classes.
	 *
	 * @return string
	 */
	function wpgen_link_more_classes( $class = '', $color = 'primary', $echo = true ) {

		$classes = get_wpgen_link_more_classes( $class, $color );

		if ( $echo ) {
			echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		} else {
			return 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
		}
	}
}
