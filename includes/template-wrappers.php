<?php
/**
 * wpgen wrappers functions
 *
 * @package wpgen
 */

if ( !defined( 'ABSPATH' ) ) exit;


// Adds custom classes to the array of body classes.
// Добавляем кастомные классы в тег body
function wpgen_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	// Добавляем класс hfeed для всех страниц кроме записей
	if ( !is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	// Добавляем класс если не активирован ни один сайдбар
	if ( !wpgen_options( 'sidebar_left_display' ) && !wpgen_options( 'sidebar_right_display' ) ) {
		$classes[] = 'no-sidebar';
	}


	// Check if the site is being viewed from a mobile device
	// Проверяем с мобильного ли устройства просматривается сайт
	if ( wp_is_mobile() ) {
		$classes[] = 'wp-mobile';
	} else {
		$classes[] = 'wp-desktop';
	}

	// Adds class with themename
	// Добавляем класс с названием темы
	$classes[] = 'theme_' . wp_get_theme()->get( 'Name' );
	$classes[] = 'theme_' . wpgen_options( 'general_color_scheme' );
	$classes[] = 'theme_' . wpgen_options( 'general_container_width' );

	// цвет беграунда элементов
	$values = array_flip( get_selected_value() );
	$elemBgColor = get_root_styles( 'elemBgColor' );
	$elemBgColorValue = $values[$elemBgColor];
	$elemBgColorSaturate = preg_replace( '/\D+/', '', $elemBgColorValue );
	$elemBgColorStyle = get_style_by_saturate( $elemBgColorSaturate );

	if ( $elemBgColorStyle ) {
		$classes[] = 'theme_elems_' . $elemBgColorStyle;
	}

	// Adds category name in the body
	// Добавляем названия категорий в <body>
/*	if ( is_single() ) {
		global $post;
		foreach(get_the_category($post->ID) as $category) {
			$classes[] = $category->category_nicename;
		}
	}*/

	return array_unique( $classes );
}
add_filter( 'body_class', 'wpgen_body_classes' );


// Adds custom classes to the array of post classes.
// Добавляем кастомные классы в тег article
function wpgen_post_classes( $classes ) {

	// Добавляем классы entry
	$classes[] = 'entry';

	if ( !in_array( 'article-single', $classes) ) {
		$classes[] = 'article-archive';
	}

	// Adds type article classes for single
	// Добавляем классы типа article для single

	if ( is_single() ) {
		if ( wpgen_options( 'single_post_template_type' ) == 'one' ) $classes[] = 'article-template-one';
		if ( wpgen_options( 'single_post_template_type' ) == 'two' ) $classes[] = 'article-template-two';
	}

	// Adds type article classes for archive
	// Добавляем классы типа article для archive
	if ( is_archive() ) {
		if ( wpgen_options( 'archive_page_template_type' ) == 'simple' ) $classes[] = 'article-template_simple';
		if ( wpgen_options( 'archive_page_template_type' ) == 'tils' ) $classes[] = 'article-template_tils';
		if ( wpgen_options( 'archive_page_template_type' ) == 'banners' ) $classes[] = 'article-template_banners';
		if ( wpgen_options( 'archive_page_template_type' ) == 'list' ) $classes[] = 'article-template_list';
	}

	$classes = apply_filters( 'wpgen_post_classes', $classes );
	return array_unique( $classes );
	
}
add_filter( 'post_class', 'wpgen_post_classes' );



// Add classes for container wrapper
// Добавляем классы контейнерам
function get_wpgen_container_classes( $class = '' ) {

	$classes = array();
	$classes[] = 'container';
	$classes[] = 'container-' . wpgen_options( 'general_container_width' );

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_container_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_container_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_container_classes( $class ) ) . '"';
	} else {
		return get_wpgen_container_classes( $class );
	}
}



// Testing
// Тестирование
/*add_filter( 'wpgen_container_classes','my_class_names' );
function my_class_names( $classes ) {

	$classes[] = 'it_is_page';

	return $classes;
}*/



// Add classes for main content wrapper
// Добавляем классы контейнерам
function get_wpgen_content_area_classes( $class = '' ) {

	$classes = array();

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
			if ( wpgen_options( 'general_content_width' ) == 'narrow' ) {
				$classes[] = 'col-md-10';
				$classes[] = 'offset-md-1';
				$classes[] = 'col-lg-8';
				$classes[] = 'offset-lg-2';
			} else {
				$classes[] = 'col-md-12';
			}
		}
	}

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_content_area_classes', $classes, $class );
	return array_unique( $classes );

}

function wpgen_content_area_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_content_area_classes( $class ) ) . '"';
	} else {
		return get_wpgen_content_area_classes( $class );
	}
}



// Add classes for sidebar wrapper
function get_wpgen_widget_area_classes( $class = '' ) {

	$classes = array();

	$classes[] = 'widget-area';
	$classes[] = 'col-12';
	$classes[] = 'col-sm-12';

	if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
		$classes[] = 'col-lg-3';
	} else {
		$classes[] = 'col-lg-4';
		$classes[] = 'col-xl-3';
	}

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_widget_area_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_widget_area_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_widget_area_classes( $class ) ) . '"';
	} else {
		return get_wpgen_widget_area_classes( $class );
	}
}



// Add classes for header
function get_wpgen_header_classes( $class = '' ) {

	$classes = array();

	// собираем массив
	$classes[] = 'site__header';
	$classes[] = 'header';

	if ( has_custom_header() ) $classes[] = 'header_background-image';

	if ( wpgen_options( 'general_top_bar_display' ) ) $classes[] = 'header_top-bar-active';

	if ( wpgen_options( 'general_header_type' ) == 'header-content' ) $classes[] = 'header_content';
	if ( wpgen_options( 'general_header_type' ) == 'header-logo-center' ) $classes[] = 'header_logo-center';
	if ( wpgen_options( 'general_header_type' ) == 'header-simple' ) $classes[] = 'header_simple';

	$classes[] = 'header_' . wpgen_options( 'general_header_color_scheme' );

	// if ( wpgen_options( 'general_menu_position' ) == 'fixed' ) {
	// 	// эта костыльная хуйня, потому что при fixed css даются хедеру и там слишком сложные условия из-за этого класса, когда меню 'absolute'
	// 	$classes[] = 'header_menu-' . wpgen_options( 'general_menu_color_scheme' );
	// }

	$classes[] = 'header_menu_' . wpgen_options( 'general_menu_position' );

	// проверяем, приняла ли функция какие-то классы или нет
	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_header_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_header_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_header_classes( $class ) ) . '"';
	} else {
		return get_wpgen_header_classes( $class );
	}
}



// Add classes for footer
function get_wpgen_footer_classes( $class = '' ) {

	$classes = array();

	$classes[] = 'site__footer';
	$classes[] = 'footer';

	if ( wpgen_options( 'general_bottom_bar_display' ) ) $classes[] = 'footer_bottom-bar-active';

	if ( wpgen_options( 'general_footer_type' ) == 'footer-simple' ) $classes[] = 'footer_simple';
	if ( wpgen_options( 'general_footer_type' ) == 'footer-three-columns' ) $classes[] = 'footer_three-columns';	
	if ( wpgen_options( 'general_footer_type' ) == 'footer-four-columns' ) $classes[] = 'footer_four-columns';

	$classes[] = 'footer_' . wpgen_options( 'general_color_scheme' );

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_footer_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_footer_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_footer_classes( $class ) ) . '"';
	} else {
		return get_wpgen_footer_classes( $class );
	}
}



// Add classes for main menu
function get_wpgen_main_menu_classes( $class = '' ) {

	$classes = array();

	$classes[] = 'main-menu';

	if ( wpgen_options( 'general_menu_type' ) == 'menu-open' ) $classes[] = 'main-menu_type-open';
	if ( wpgen_options( 'general_menu_type' ) == 'menu-close' ) $classes[] = 'main-menu_type-close';

	$classes[] = 'main-menu-' . wpgen_options( 'general_menu_position' );
	if ( wpgen_options( 'general_menu_position' ) == 'absolute' ) {
		// эта костыльная хуйня, потому что при fixed css даются хедеру и там слишком сложные условия из-за этого класса, когда меню 'absolute'
		$classes[] = 'main-menu-' . wpgen_options( 'general_menu_color_scheme' );
	}
	$classes[] = 'main-menu-' . wpgen_options( 'general_menu_align' );

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_main_menu_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_main_menu_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_main_menu_classes( $class ) ) . '"';
	} else {
		return get_wpgen_main_menu_classes( $class );
	}
}





// Add classes for meta display single template TWO
function get_wpgen_meta_display_classes( $class = '' ) {

	$classes = array();

	$classes[] = 'entry__meta';

	if ( wpgen_options( 'single_post_template_type' ) == 'one' ) $classes[] = 'entry__meta_inline';
	if ( wpgen_options( 'single_post_template_type' ) == 'two' ) $classes[] = 'entry__meta_list';

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_meta_display_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_meta_display_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_meta_display_classes( $class ) ) . '"';
	} else {
		return get_wpgen_meta_display_classes( $class );
	}
}



// Add classes for index page columns
function get_wpgen_index_page_columns_classes( $class = '' ) {

	$classes = array();
	$classes[] = 'article-column';
	$classes[] = 'col-12';

	if ( wpgen_options( 'index_page_columns' ) == 'four' ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'col-lg-4';
		$classes[] = 'col-xl-3';
		$classes[] = 'article-column-4';
	}
	if ( wpgen_options( 'index_page_columns' ) == 'three' ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'col-lg-4';
		$classes[] = 'article-column-3';
	}
	if ( wpgen_options( 'index_page_columns' ) == 'two' ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'article-column-2';
	}	
	if ( wpgen_options( 'index_page_columns' ) == 'one' ) {
		$classes[] = 'col-sm-12';
		$classes[] = 'article-column-1';
	}

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_index_page_columns_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_index_page_columns_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_index_page_columns_classes( $class ) ) . '"';
	} else {
		return get_wpgen_index_page_columns_classes( $class );
	}
}



// Add classes for archive page columns
function get_wpgen_archive_page_columns_classes( $class = '' ) {

	$classes = array();
	$classes[] = 'article-column';
	$classes[] = 'col-12';

	if ( wpgen_options( 'archive_page_columns' ) == 'six' ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'col-lg-3';
		$classes[] = 'col-xl-2';
		$classes[] = 'article-column-6';
	}
	if ( wpgen_options( 'archive_page_columns' ) == 'five' ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'col-lg-3';
		$classes[] = 'col-xl-5th';
		$classes[] = 'article-column-5';
	}
	if ( wpgen_options( 'archive_page_columns' ) == 'four' ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'col-lg-4';
		$classes[] = 'col-xl-3';
		$classes[] = 'article-column-4';
	}
	if ( wpgen_options( 'archive_page_columns' ) == 'three' ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'col-lg-4';
		$classes[] = 'article-column-3';
	}
	if ( wpgen_options( 'archive_page_columns' ) == 'two' ) {
		$classes[] = 'col-sm-6';
		$classes[] = 'article-column-2';
	}	
	if ( wpgen_options( 'archive_page_columns' ) == 'one' ) {
		$classes[] = 'col-sm-12';
		$classes[] = 'article-column-1';
	}

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_archive_page_columns_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_archive_page_columns_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_archive_page_columns_classes( $class ) ) . '"';
	} else {
		return get_wpgen_archive_page_columns_classes( $class );
	}
}




// Add classes for archive page wrapper columns
function get_wpgen_archive_page_columns_wrapper_classes( $class = '' ) {

	$classes = array();
	$classes[] = 'row';

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_archive_page_columns_wrapper_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_archive_page_columns_wrapper_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_archive_page_columns_wrapper_classes( $class ) ) . '"';
	} else {
		return get_wpgen_archive_page_columns_wrapper_classes( $class );
	}
}



// Add classes for meta display single template TWO
function get_wpgen_link_more_classes( $class = '', $color = null ) {

	$classes = array();

	if ( wpgen_options( 'archive_page_detail_button' ) == 'button' ) {
		$classes[] = 'button';
		$classes[] = 'button_more';
	} else {
		$classes[] = 'link';
		$classes[] = 'link_more';
	}

	if ( wpgen_options( 'archive_page_detail_button' ) == 'button' ) {

		if ( wpgen_options( 'general_button_type' ) === 'common' ) {
			if ( $color !== null ) {
				$classes[] = 'button-' . $color;
			}
		} else {
			$classes[] = 'button-' . wpgen_options( 'general_button_type' );

			if ( $color !== null ) {
				$classes[] = 'button-' . wpgen_options( 'general_button_type' ) . '-' . $color;
			}
		}
	}

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'wpgen_link_more_classes', $classes, $class );
	return array_unique( $classes );
}

function wpgen_link_more_classes( $class = '', $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_wpgen_link_more_classes( $class ) ) . '"';
	} else {
		return get_wpgen_link_more_classes( $class );
	}
}



// Add classes for buttons
function get_button_classes( $class = '', $color = null ) {

	$classes = array();
	$classes[] = 'button';

	if ( wpgen_options( 'general_button_type' ) === 'common' ) {
		if ( $color !== null ) {
			$classes[] = 'button-' . $color;
		}
	} else {
		$classes[] = 'button-' . wpgen_options( 'general_button_type' );

		if ( $color !== null ) {
			$classes[] = 'button-' . wpgen_options( 'general_button_type' ) . '-' . $color;
		}
	}

	if ( $class ) $classes[] = $class;

	$classes = array_map( 'esc_attr', $classes );
	$classes = apply_filters( 'button_classes', $classes, $class );
	return array_unique( $classes );
}

function button_classes( $class = '', $color = null, $echo = true ) {
	if ( $echo ) {
		echo 'class="' . implode( ' ', get_button_classes( $class, $color ) ) . '"';
	} else {
		return get_button_classes( $class, $color );
	}
}