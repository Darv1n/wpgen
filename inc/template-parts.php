<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'get_wpgen_site_branding' ) ) {

	/**
	 * Get $output for site branding.
	 *
	 * @param string $output parameter for filter.
	 *
	 * @return string
	 */
	function get_wpgen_site_branding( $output = '' ) {

		// Check if the image is set in the customizer settings or display the text.
		if ( has_custom_logo() ) {
			$output .= get_custom_logo();
		} else {
			// For all pages except the main page, display a link to it.
			if ( ( is_front_page() || is_home() ) && ! is_paged() ) {
				$output .= '<div class="logo">';
					$output .= '<strong class="logo__title">' . get_bloginfo( 'name' ) . '</strong>';
					$output .= '<p class="logo__description">' . get_bloginfo( 'description' ) . '</p>';
				$output .= '</div>';
			} else {
				$output .= '<a class="logo" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
					$output .= '<strong class="logo__title">' . get_bloginfo( 'name' ) . '</strong>';
					$output .= '<p class="logo__description">' . get_bloginfo( 'description' ) . '</p>';
				$output .= '</a>';
			}
		}

		// Filter html output.
		return apply_filters( 'get_wpgen_site_branding', $output );
	}
}

if ( ! function_exists( 'the_wpgen_site_branding' ) ) {

	/**
	 * Display $output for site branding.
	 *
	 * @param string $before HTML before site branding.
	 * @param string $after  HTML after site branding.
	 * @param bool   $echo   echo or return html output.
	 *
	 * @return string
	 */
	function the_wpgen_site_branding( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_site_branding();

		$output = apply_filters( 'the_wpgen_site_branding', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}

if ( ! function_exists( 'get_wpgen_post_meta_list' ) ) {

	/**
	 * Get $output for post meta information.
	 *
	 * @param string $output parameter for filter.
	 *
	 * @return string
	 */
	function get_wpgen_post_meta_list( $output = '' ) {

		if ( wpgen_options( 'single_' . get_post_type() . '_meta_author_display' ) ) {
			$output .= '<li class="post-meta__item icon icon_user">';
				$output .= '<a class="' . esc_attr( implode( ' ', get_link_classes( 'meta__link' ) ) ) . '" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . get_escape_title( get_the_author() ) . '</a>';
			$output .= '</li>';
		}

		if ( wpgen_options( 'single_' . get_post_type() . '_meta_date_display' ) ) {
			$output .= '<li class="post-meta__item icon icon_calendar">';
				$output .= '<time class="post-date post-date-published data-title" datetime="' . get_the_date( 'Y-m-d\TH:i:sP' ) . '" data-title="' . esc_attr( __( 'Published Date', 'wpgen' ) ) . '">' . get_the_date( 'j M, Y' ) . '</time>';
				if ( wpgen_options( 'single_' . get_post_type() . '_meta_date_modified_display' ) && get_the_modified_date( 'j M, Y' ) !== get_the_date( 'j M, Y' ) ) {
					$output .= '<time class="post-date post-date-modified data-title" datetime="' . get_the_modified_date( 'Y-m-d\TH:i:sP' ) . '" data-title="' . esc_attr( __( 'Modified Date', 'wpgen' ) ) . '">(' . get_the_modified_date( 'j M, Y' ) . ')</time>';
				}
			$output .= '</li>';
		}

		if ( get_post_type() === 'post' ) {
			if ( wpgen_options( 'single_' . get_post_type() . '_meta_cats_display' ) && has_category() ) {
				$output .= '<li class="post-meta__item icon icon_folder">';
					$categories = get_the_category();
					foreach ( $categories as $key => $category ) {
						$list[] = '<a class="' . esc_attr( implode( ' ', get_link_classes() ) ) . '" href="' . esc_url( get_term_link( $category->term_id, $category->taxonomy ) ) . '">' . esc_html( $category->name ) . '</a>';
					}
					$output .= implode( ', ', $list );
				$output .= '</li>';
			}
			if ( wpgen_options( 'single_' . get_post_type() . '_meta_tags_display' ) && has_tag() ) {
				$output .= '<li class="post-meta__item icon icon_tag">';
					$tags = get_the_tags();
					foreach ( $tags as $key => $tag ) {
						$list[] = '<a class="' . esc_attr( implode( ' ', get_link_classes() ) ) . '" href="' . esc_url( get_term_link( $tag->term_id, $tag->taxonomy ) ) . '">' . esc_html( $tag->name ) . '</a>';
					}
					$output .= implode( ', ', $list );
				$output .= '</li>';
			}
		}

		if ( wpgen_options( 'single_' . get_post_type() . '_meta_time_display' ) ) {
			$output .= '<li class="post-meta__item icon icon_clock data-title" data-title="' . esc_attr( __( 'Reading speed', 'wpgen' ) ) . '">';
				$output .= read_time_estimate( get_the_content() ) . ' ' . esc_html__( 'min.', 'wpgen' );
			$output .= '</li>';
		}

		if ( wpgen_options( 'single_' . get_post_type() . '_meta_comments_display' ) ) {
			$output .= '<li class="post-meta__item icon icon_comment">';
				$output .= '<a class="' . esc_attr( implode( ' ', get_link_classes( 'meta__link' ) ) ) . '" href="' . esc_url( get_comments_link() ) . '" rel="bookmark">' . esc_html__( 'Comments', 'wpgen' ) . ': ' . get_comments_number() . '</a>';
			$output .= '</li>';
		}

		// Filter html output.
		$output = apply_filters( 'get_wpgen_post_meta_list', $output );

		if ( wpgen_options( 'single_' . get_post_type() . '_meta_edit_display' ) && is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			$output .= '<li class="post-meta__item icon icon_pen">';
				$output .= '<a class="' . esc_attr( implode( ' ', get_link_classes( 'edit-link' ) ) ) . '" href="' . esc_url( get_edit_post_link() ) . '">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</li>';
		}

		return $output;
	}
}

if ( ! function_exists( 'the_wpgen_post_meta_list' ) ) {

	/**
	 * Display content post meta information with filters.
	 *
	 * @param string $before HTML before site branding.
	 * @param string $after  HTML after site branding.
	 * @param bool   $echo   echo or return html output.
	 *
	 * @return string
	 */
	function the_wpgen_post_meta_list( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_post_meta_list();

		$output = apply_filters( 'the_wpgen_post_meta_list', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}

if ( ! function_exists( 'get_wpgen_archive_meta_list' ) ) {

	/**
	 * Get $output for archive meta information.
	 *
	 * @param string $output is parameter for filter.
	 *
	 * @return string
	 */
	function get_wpgen_archive_meta_list( $output = '' ) {

		if ( wpgen_options( 'archive_' . get_post_type() . '_meta_author_display' ) ) {
			$output .= '<li class="post-meta__item icon icon_user">';
				$output .= '<a class="' . esc_attr( implode( ' ', get_link_classes( 'meta__link' ) ) ) . '" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . get_escape_title( get_the_author() ) . '</a>';
			$output .= '</li>';
		}

		if ( wpgen_options( 'archive_' . get_post_type() . '_meta_date_display' ) ) {
			$output .= '<li class="post-meta__item icon icon_calendar">';
				$output .= '<time class="post-date published data-title" datetime="' . get_the_date( 'Y-m-d\TH:i:sP' ) . '" data-title="' . esc_attr( __( 'Published Date', 'wpgen' ) ) . '">' . get_the_date( 'j F, Y' ) . '</time>';
			$output .= '</li>';
		}

		if ( get_post_type() === 'post' ) {
			if ( wpgen_options( 'archive_' . get_post_type() . '_meta_cats_display' ) && has_category() ) {
				$output .= '<li class="post-meta__item icon icon_folder">';
					$output .= get_the_category_list( ', ' );
				$output .= '</li>';
			}
			if ( wpgen_options( 'archive_' . get_post_type() . '_meta_tags_display' ) && has_tag() ) {
				$output .= '<li class="post-meta__item icon icon_tag">';
					$output .= get_the_tag_list( '', ', ' );
				$output .= '</li>';
			}
		}

		if ( wpgen_options( 'archive_' . get_post_type() . '_meta_time_display' ) && get_post_meta( get_the_ID(), 'read_time', true ) ) {
			$output .= '<li class="post-meta__item icon icon_clock data-title" data-title="' . esc_attr( __( 'Reading speed', 'wpgen' ) ) . '">';
				$output .= read_time_estimate( get_the_content() ) . ' ' . esc_html__( 'min.', 'wpgen' );
			$output .= '</li>';
		}

		if ( wpgen_options( 'archive_' . get_post_type() . '_meta_comments_display' ) ) {
			$output .= '<li class="post-meta__item icon icon_comment">';
				$output .= '<a class="' . esc_attr( implode( ' ', get_link_classes( 'meta__link' ) ) ) . '" href="' . esc_url( get_comments_link() ) . '" rel="bookmark">' . esc_html__( 'Comments', 'wpgen' ) . ': ' . get_comments_number() . '</a>';
			$output .= '</li>';
		}

		// Filter html output.
		$output = apply_filters( 'get_wpgen_archive_meta_list', $output );

		if ( wpgen_options( 'archive_' . get_post_type() . '_meta_edit_display' ) && is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			$output .= '<li class="post-meta__item icon icon_pen-to-square">';
				$output .= '<a class="' . esc_attr( implode( ' ', get_link_classes( 'edit-link' ) ) ) . '" href="' . esc_url( get_edit_post_link() ) . '">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</li>';
		}

		return $output;
	}
}

if ( ! function_exists( 'the_wpgen_archive_meta_list' ) ) {

	/**
	 * Display content archive meta information with filters.
	 *
	 * @param string $before HTML before site branding.
	 * @param string $after  HTML after site branding.
	 * @param bool   $echo   echo or return html output.
	 *
	 * @return string
	 */
	function the_wpgen_archive_meta_list( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_archive_meta_list();

		$output = apply_filters( 'the_wpgen_archive_meta_list', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}

add_action( 'before_site_content', 'wpgen_section_content_wrapper_start', 50 );
if ( ! function_exists( 'wpgen_section_content_wrapper_start' ) ) {

	/**
	 * Display section content wrapper start in header.php.
	 */
	function wpgen_section_content_wrapper_start() {

		$output = '<section id="section-content" class="section section_content" aria-label="' . _x( 'Content section', 'aria-label', 'wpgen' ) . '">';
			$output .= '<div class="' . esc_attr( implode( ' ', get_wpgen_container_classes() ) ) . '">';
				$output .= '<div class="row">';

		// Filter html output.
		echo apply_filters( 'wpgen_section_content_wrapper_start', $output );
	}
}

// Usage:
/*add_filter( 'wpgen_section_content_wrapper_start', 'my_section_content_wrapper_start' );
if ( ! function_exists( 'my_section_content_wrapper_start' ) ) {
	function my_section_content_wrapper_start( $output ) {

		if ( is_front_page() ) {
			return '';
		}

		return $output;
	}
}*/

add_action( 'after_site_content', 'wpgen_section_content_wrapper_end', 50 );
if ( ! function_exists( 'wpgen_section_content_wrapper_end' ) ) {

	/**
	 * Display section content wrapper end in footer.php.
	 */
	function wpgen_section_content_wrapper_end() {

				$output = '</div>';
			$output .= '</div>';
		$output .= '</section>';

		// Filter html output.
		echo apply_filters( 'wpgen_section_content_wrapper_end', $output );
	}
}

// Usage:
/*add_filter( 'wpgen_section_content_wrapper_end', 'my_section_content_wrapper_end' );
if ( ! function_exists( 'my_section_content_wrapper_end' ) ) {
	function my_section_content_wrapper_end( $output ) {

		if ( is_front_page() ) {
			return '';
		}

		return $output;
	}
}*/

// Usage:
/*add_filter( 'wpgen_section_content_wrapper_start', 'remove_front_page_section_content_wrapper' );
add_filter( 'wpgen_section_content_wrapper_end', 'remove_front_page_section_content_wrapper' );
if ( ! function_exists( 'remove_front_page_section_content_wrapper' ) ) {
	function remove_front_page_section_content_wrapper( $output ) {

		if ( is_front_page() ) {
			return '';
		}

		return $output;
	}
}*/


add_action( 'wpgen_before_main_navigation', 'wpgen_menu_toggle', 10 );
if ( ! function_exists( 'wpgen_menu_toggle' ) ) {

	/**
	 * Display the menu button on action hook wpgen_before_main_navigation.
	 */
	function wpgen_menu_toggle() {

		$output           = '';
		$classes          = array();
		$menu_button_type = wpgen_options( 'general_menu_button_type' );

/*		if ( $menu_button_type === 'button-icon-text' ) {
			$classes[] = 'menu-toggle_button';
			$classes[] = 'menu-toggle_icon';
		} elseif ( $menu_button_type === 'button-icon' ) {
			$classes[] = 'menu-toggle_button';
			$classes[] = 'menu-toggle_icon';
			$classes[] = 'menu-toggle_solo-icon';
		} elseif ( $menu_button_type === 'button-text' ) {
			$classes[] = 'menu-toggle_button';
		} elseif ( $menu_button_type === 'icon' ) {
			$classes[] = 'menu-toggle_icon';
			$classes[] = 'menu-toggle_solo-icon';
		} elseif ( $menu_button_type === 'icon-text' ) {
			$classes[] = 'menu-toggle_icon';
			$classes[] = 'menu-toggle_text';
		} else {
			$classes[] = 'menu-toggle_text';
		}*/

		if ( in_array( $menu_button_type, array( 'button-icon-text', 'icon-text', ), true ) ) {
			$classes[] = 'icon';
			$classes[] = 'icon_menu';
			$classes[] = 'icon_' . wpgen_options( 'general_menu_button_icon_position' );
		} elseif( $menu_button_type === 'button-icon' ) {
			$classes[] = 'button-square';
		}

		if ( in_array( $menu_button_type, array( 'button-icon-text', 'button-icon', 'button-text' ), true )  ) {
			$classes = get_button_classes( $classes );
		} else {
			$classes[] = 'button-reset';
		}

		// // Иконка справа/слева.
		// if ( in_array( $menu_button_type, array( 'button-icon-text', 'icon-text' ), true ) ) {
		// 	$classes[] = 'menu-toggle_icon-' . wpgen_options( 'general_menu_button_icon_position' );
		// }

		$classes[] = 'menu-toggle';
		$classes[] = 'menu-toggle_' . wpgen_options( 'general_menu_button_alignment' );
		$classes   = apply_filters( 'get_menu_toggle_button_classes', $classes );

		$output .= '<button id="menu-toggle" class="' . esc_attr( implode( ' ', $classes ) ) . '">';
			if ( in_array( $menu_button_type, array( 'icon', 'button-icon' ), true ) ) {
				$output .= '<i class="icon icon_menu"></i>';
			} else {
				$output .= esc_html__( 'Menu', 'wpgen' );
			}
		$output .= '</button>';

		// Filter html output.
		echo apply_filters( 'wpgen_menu_toggle', $output );
	}
}

// Usage:
/*add_filter( 'wpgen_menu_toggle', 'my_menu_toggle' );
if ( ! function_exists( 'my_menu_toggle' ) ) {
	function my_menu_toggle( $output ) {

		$output = '';

		$classes[] = 'menu-toggle';
		$menu_button_type = wpgen_options( 'general_menu_button_type' );
		if ( $menu_button_type === 'button' ) $classes[] = 'button';

		$output .= '<button id="menu-toggle hello" class="' . implode(' ', $classes) . '">';

			if ( $menu_button_type !== 'burger' ) $output .= '<span class="menu-toggle__text">' . __( 'Menu', 'wpgen' ) . '</span>';
			if ( $menu_button_type === 'burger' || $menu_button_type === 'text-burger' ) $output .= '<span class="menu-toggle__icon"></span>';

		$output .= '</button>';

		return $output;
	}
}*/

add_action( 'wp_footer_close', 'wpgen_scroll_top', 10 );
if ( ! function_exists( 'wpgen_scroll_top' ) ) {

	// Display scroll to top button on action hook wp_footer_close.
	function wpgen_scroll_top() {

		$output  = '';
		$classes = array();

		if ( wpgen_options( 'general_scroll_top_button_display' ) ) {
			$scroll_top_type = wpgen_options( 'general_scroll_top_button_type' );

			if ( in_array( $scroll_top_type, array( 'button-icon-text', 'icon-text', ), true ) ) {
				$classes[] = 'icon';
				$classes[] = 'icon_arrow-up';
				$classes[] = 'icon_' . wpgen_options( 'general_scroll_top_button_icon_position' );
			} elseif( $scroll_top_type === 'button-icon' ) {
				$classes[] = 'button-square';
			}

			if ( in_array( $scroll_top_type, array( 'button-icon-text', 'button-icon', 'button-text' ), true )  ) {
				$classes = get_button_classes( $classes );
			} else {
				$classes[] = 'button-reset';
			}

			$classes[] = 'scroll-top';
			$classes[] = 'scroll-top_' . wpgen_options( 'general_scroll_top_button_alignment' );
			$classes   = apply_filters( 'get_scroll_top_button_classes', $classes );

			$output .= '<button id="scroll-top" class="' . esc_attr( implode( ' ', $classes ) ) . '">';
			if ( in_array( $scroll_top_type, array( 'icon', 'button-icon' ), true ) ) {
				$output .= '<i class="icon icon_arrow-up"></i>';
			} else {
				$output .= esc_html__( 'Scroll up', 'wpgen' );
			}
			$output .= '</button>';
		}

		// Filter html output.
		echo apply_filters( 'wpgen_scroll_top', $output );
	}
}

add_action( 'wp_footer_close', 'wpgen_cookie_accepter', 20 );
if ( ! function_exists( 'wpgen_cookie_accepter' ) ) {

	/**
	 * Display cookie accepter on action hook wp_footer_close.
	 */
	function wpgen_cookie_accepter() {

		$output = '';

		if ( ! is_user_logged_in() && wpgen_options( 'general_cookie_display' ) ) {
			$output .= '<div id="cookie" class="cookie" style="display: none">';
				$output .= '<div class="' . esc_attr( implode( ' ', get_wpgen_container_classes() ) ) . '">';
					$output .= '<div class="row align-items-center">';
						$output .= '<div class="col-12 col-lg-8 cookie__message">';
							$output .= '<p class="small">' . __( 'We use cookies on our website to give you the most relevant experience by remembering your preferences and repeat visits. <br>By clicking «Accept», you consent to the use of ALL the cookies', 'wpgen' ) . '</p>';
						$output .= '</div>';
						$output .= '<div class="col-12 col-lg-2 cookie__privacy">';
							$output .= '<p><a class="' . esc_attr( implode( ' ', get_link_classes() ) ) . '" href="' . esc_url( get_privacy_policy_url() ) . '" tabindex="0">' . esc_html__( 'Cookie settings', 'wpgen' ) . '</a></p>';
						$output .= '</div>';
						$output .= '<div class="col-12 col-lg-2 cookie__confirm">';
							$output .= '<button id="cookie_action" class="' . esc_attr( implode( ' ', get_button_classes() ) ) . '">' . esc_html__( 'Accept', 'wpgen' ) . '</button>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		}

		// Filter html output.
		echo apply_filters( 'wpgen_cookie_accepter', $output );
	}
}

add_action( 'before_site_content', 'wpgen_breadcrumbs', 10 );
if ( ! function_exists( 'wpgen_breadcrumbs' ) ) {

	/**
	 * Display breadcrumbs on action hook wp_footer_close.
	 */
	function wpgen_breadcrumbs( $before = '', $after  = '' ) {

		if ( ! is_front_page() && ! is_home() && wpgen_options( 'general_breadcrumbs_display' ) ) {

			$before .= '<section id="section-breadcrumbs" class="section section_breadcrumbs">';
				$before .= '<div class="' . esc_attr( implode( ' ', get_wpgen_container_classes() ) ) . '">';
					$before .= '<div class="row">';
						$before .= '<div class="col-12 align-items-center">';

						$after .= '</div>';
					$after .= '</div>';
				$after .= '</div>';
			$after .= '</section>';

			if ( wpgen_options( 'general_breadcrumbs_type' ) === 'navxt' && is_plugin_active( 'breadcrumb-navxt/breadcrumb-navxt.php' ) ) {

				$before .= '<nav id="breadcrumbs" class="breadcrumbs breadcrumbs_' . esc_attr( wpgen_options( 'general_breadcrumbs_type' ) ) . '" typeof="BreadcrumbList" vocab="https://schema.org/" aria-label="breadcrumb">';
					$before .= '<ol class="list-inline list-unstyled">';
					$after  .= '</ol>';
				$after  .= '</nav>';
			} else {
				$before .= '<div id="breadcrumbs" class="breadcrumbs breadcrumbs_' . esc_attr( wpgen_options( 'general_breadcrumbs_type' ) ) . '">';
				$after  .= '</div>';
			}

			if ( wpgen_options( 'general_breadcrumbs_type' ) === 'navxt' && is_plugin_active( 'breadcrumb-navxt/breadcrumb-navxt.php' ) ) {
				echo $before;
					bcn_display_list();
				echo $after;
			} elseif ( wpgen_options( 'general_breadcrumbs_type' ) === 'kama' && class_exists( 'Kama_Breadcrumbs' ) ) {
				echo $before;
					kama_breadcrumbs();
				echo $after;
			} elseif ( wpgen_options( 'general_breadcrumbs_type' ) === 'yoast' && is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
				echo $before;
					yoast_breadcrumb( '<nav class="breadcrumbs__navigation">', '</nav>' );
				echo $after;
			} elseif ( wpgen_options( 'general_breadcrumbs_type' ) === 'rankmath' && is_plugin_active( 'seo-by-rank-math/rank-math.php' ) ) {
				echo $before;
					rank_math_the_breadcrumbs();
				echo $after;
			} elseif ( wpgen_options( 'general_breadcrumbs_type' ) === 'seopress' && is_plugin_active( 'wp-seopress/seopress.php' ) ) {
				echo $before;
					seopress_display_breadcrumbs();
				echo $after;
			} elseif ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				echo $before;
					woocommerce_breadcrumb();
				echo $after;
			}
		}
	}
}

if ( ! function_exists( 'get_wpgen_post_navigation' ) ) {

	/**
	 * Get $output for post navigation.
	 *
	 * @param string $output parameter for filter.
	 *
	 * @return string
	 */
	function get_wpgen_post_navigation( $output = '' ) {

		if ( wpgen_options( 'single_' . get_post_type() . '_post_nav_display' ) && get_previous_post_link() && get_next_post_link() && is_single() ) {

			$output .= '<nav class="navigation post-navigation" role="navigation">';
				$output .= '<div class="row">';
					$output .= '<div class="col-12 col-md-6">';
						$output .= get_previous_post_link( '<div class="post-navigation__item post-navigation__item_previous">%link</div>', '<span class="post--title">← %title</span>' );
					$output .= '</div>';
					$output .= '<div class="col-12 col-md-6">';
						$output .= get_next_post_link( '<div class="post-navigation__item post-navigation__item_next">%link</div>', '<span class="post--title">%title →</span>' );
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</nav>';

			// Filter html output.
			return apply_filters( 'get_wpgen_post_navigation', $output );
		}
	}
}

add_action( 'wpgen_before_comment_form', 'the_wpgen_post_navigation', 15 );
if ( ! function_exists( 'the_wpgen_post_navigation' ) ) {

	/**
	 * Display content post navigation with filters on action hook wpgen_before_comment_form.
	 *
	 * @param string $before HTML before post navigation.
	 * @param string $after  HTML after post navigation.
	 * @param bool   $echo   echo or return html output.
	 *
	 * @return string
	 */
	function the_wpgen_post_navigation( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_post_navigation();

		$output = apply_filters( 'the_wpgen_post_navigation', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}

if ( ! function_exists( 'get_wpgen_posts_navigation' ) ) {

	/**
	 * Get $output for archive navigation.
	 *
	 * @param string $output parameter for filter.
	 *
	 * @return string
	 */
	function get_wpgen_posts_navigation( $output = '' ) {

		global $paged;
		global $wp_query;

		$pages           = $wp_query->max_num_pages;
		$range           = 2;
		$showitems       = ( $range * 2 ) + 1;
		$post_pagination = wpgen_options( 'archive_' . get_post_type() . '_pagination' );

		if ( ! $pages || empty( $paged ) ) {
			$paged = 1;
		}

		if ( (int) $pages === 1 ) {
			return;
		}

		$output .= '<nav class="navigation posts-navigation posts-navigation_' . esc_attr( $post_pagination ) . '" data-max-pages="' . esc_attr( $pages ) . '" role="navigation" aria-label="' . esc_html__( 'Site post navigation', 'wpgen' ) . '">';

		// Choose numeric or older/newer pagination from customizer option.
		if ( $post_pagination === 'numeric' ) {

			// First page.
			if ( $paged > 3 ) {
				$output .= '<a class="' . esc_attr( implode( ' ', get_button_classes( 'posts-navigation__item posts-navigation__item_first button-small icon icon_center icon_chevron-left' ) ) ) . '" href="' . esc_url( get_pagenum_link( 1 ) ) . '" role="button">-1</a>';
			}

			// Основной цикл вывода ссылок.
			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {

					if ( $paged === $i ) {
						$output .= '<span class="' . esc_attr( implode( ' ', get_button_classes( 'button-small button-disabled posts-navigation__item posts-navigation__item_current' ) ) ) . '">' . $i . '</span>';
					} else {

						if ( $paged === $i ) {
							$classes = 'button-small posts-navigation__item posts-navigation__item_current';
						} elseif ( $paged + 1 === $i ) {
							$rel     = ' rel="next"';
							$classes = 'button-small posts-navigation__item posts-navigation__item_next';
						} elseif ( $paged > 1 && $paged - 1 === $i ) {
							$rel     = ' rel="prev"';
							$classes = 'button-small posts-navigation__item posts-navigation__item_prev';
						} else {
							$rel     = '';
							$classes = 'button-small posts-navigation__item';
						}

						$output .= '<a class="' . esc_attr( implode( ' ', get_button_classes( $classes ) ) ) . '" href="' . esc_url( get_pagenum_link( $i ) ) . '" role="button"' . $rel . '>' . $i . '</a>';
					}
				}
			}

			// Last Page.
			if ( $pages > 5 && $paged < $pages - 2 ) {
				$output .= '<a class="' . esc_attr( implode( ' ', get_button_classes( 'posts-navigation__item posts-navigation__item_last button-small icon icon_center icon_chevron-right' ) ) ) . '" href="' . esc_url( get_pagenum_link( $pages ) ) . '" role="button">+1</a>';
			}

		} else {

			// Default Pagination.
			$output .= '<div class="row">';

			if ( get_next_posts_link() ) {
				$output .= '<div class="col-12 col-md-6">';
					$output .= '<div class="posts-navigation__item_prev">';
						next_posts_link( esc_html__( 'Older Posts', 'wpgen' ) );
					$output .= '</div>';
				$output .= '</div>';
			}

			if ( get_previous_posts_link() ) {
				$output .= '<div class="col-12 col-md-6">';
					$output .= '<div class="posts-navigation__item_next">';
						previous_posts_link( esc_html__( 'Newer Posts', 'wpgen' ) );
					$output .= '</div>';
				$output .= '</div>';
			}

			$output .= '</div>';
		}

		$output .= '</nav>';

		wp_reset_postdata();

		// Filter html output.
		return apply_filters( 'get_wpgen_posts_navigation', $output );
	}
}

if ( ! function_exists( 'the_wpgen_posts_navigation' ) ) {

	/**
	 * Display content archive posts navigation with filters.
	 *
	 * @param string $before HTML before posts navigation.
	 * @param string $after  HTML after posts navigation.
	 * @param bool   $echo   echo or return html output.
	 *
	 * @return string
	 */
	function the_wpgen_posts_navigation( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_posts_navigation();

		$output = apply_filters( 'the_wpgen_archive_meta_list', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}

if ( ! function_exists( 'get_wpgen_entry_footer' ) ) {

	/**
	 * Get $output for the pages, categories, tags and edit link.
	 *
	 * @param string $output is parameter for filter.
	 *
	 * @return string
	 */
	function get_wpgen_entry_footer( $output = '' ) {

		// Get page navigation links for multi-page posts (<!--nextpage--> is used for separation, one or more times in the content).
		$output .= wp_link_pages( array(
			'before' => '<div class="post-footer__item post-footer__pages">' . esc_html__( 'Pages:', 'wpgen' ),
			'after'  => '</div>',
			'echo'   => 0,
		) );

		if ( get_post_type() === 'post' ) {
			if ( wpgen_options( 'single_' . get_post_type() . '_entry_footer_cats_display' ) && has_category() ) {
				$output .= '<div class="post-footer__item post-footer__cats">';
					$output .= '<strong>' . esc_html__( 'Post categories', 'wpgen' ) . ':</strong>';
					$output .= '<ul class="post-footer__list">';
						$categories = get_the_category();
						foreach ( $categories as $key => $category ) {
							$output .= '<li><a class="' . esc_attr( implode( ' ', get_link_classes() ) ) . '" href="' . esc_url( get_term_link( $category->term_id, $category->taxonomy ) ) . '">' . esc_html( $category->name ) . '</a></li>';
						}
					$output .= '</ul>';
				$output .= '</div>';
			}
			if ( wpgen_options( 'single_' . get_post_type() . '_entry_footer_tags_display' ) && has_tag() ) {
				$output .= '<div class="post-footer__item post-footer__tags">';
					$output .= '<strong>' . esc_html__( 'Post tags', 'wpgen' ) . ':</strong>';
					$output .= '<ul class="post-footer__list">';
						$tags = get_the_tags();
						foreach ( $tags as $key => $tag ) {
							$output .= '<li><a class="' . esc_attr( implode( ' ', get_link_classes() ) ) . '" href="' . esc_url( get_term_link( $tag->term_id, $tag->taxonomy ) ) . '">#' . esc_html( $tag->name ) . '</a></li>';
						}
					$output .= '</ul>';
				$output .= '</div>';
			}
		}

		// Get edit link, if the checkbox is set in the customizer settings and the user has enough rights.
		if ( wpgen_options( 'single_' . get_post_type() . '_meta_edit_display' ) && is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			$output .= '<div class="post-footer__item post-footer__edit">';
				$output .= '<a class="' . esc_attr( implode( ' ', get_link_classes( 'edit-link' ) ) ) . '" href="' . esc_url( get_edit_post_link() ) . '">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</div>';
		}

		// Filter html output.
		return apply_filters( 'get_wpgen_entry_footer', $output );
	}
}

if ( ! function_exists( 'the_wpgen_entry_footer' ) ) {

	/**
	 * Display content entry_footer with filters.
	 *
	 * @param string $before HTML before content entry_footer.
	 * @param string $after  HTML after content entry_footer.
	 * @param bool   $echo   echo or return html output.
	 *
	 * @return string
	 */
	function the_wpgen_entry_footer( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_entry_footer();

		$output = apply_filters( 'the_wpgen_entry_footer', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo ) {
			echo $output;
		} else {
			return $output;
		}
	}
}

add_action( 'wpgen_after_comment_form', 'the_wpgen_similar_posts', 15 );
if ( ! function_exists( 'the_wpgen_similar_posts' ) ) {

	/**
	 * Display similar posts on action hook wpgen_after_comment_form.
	 */
	function the_wpgen_similar_posts() { ?>

		<?php if ( get_post_type() === 'post' && wpgen_options( 'single_post_similar_posts_display' ) ) { ?>

			<section id="similar-posts" class="section section_similar-posts similar-posts" aria-label="<?php echo _x( 'Similar posts', 'aria-label', 'wpgen' ); ?>">

				<h2 class="section-title"><?php esc_html_e( 'Similar posts', 'wpgen' ); ?></h2>

				<?php

					$args = array(
						'post__not_in'   => array( get_the_ID() ),
						'order'          => wpgen_options( 'single_post_similar_posts_order' ),
						'orderby'        => wpgen_options( 'single_post_similar_posts_orderby' ),
						'posts_per_page' => wpgen_options( 'single_post_similar_posts_count' ),
					);

					if ( has_category() ) {
						$cats_object = get_the_category();
						foreach ( $cats_object as $key => $cat ) {
							$cats[] = $cat->term_id;
						}
						$args['category__in'] = $cats;
					}

					if ( has_tag() ) {
						$tags_object = get_the_tags();
						foreach ( $tags_object as $key => $tag ) {
							$tags[] = $tag->term_id;
						}
						$args['tag__in'] = $tags;
					}

					$query = new wp_query( $args );

					if ( $query->have_posts() ) : ?>

						<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>

						<?php while ( $query->have_posts() ) : ?>
							<?php $query->the_post(); ?>

							<div <?php wpgen_archive_page_columns_classes(); ?>>

								<?php
									// Get a template with a post type, if there is one in the theme.
									if ( file_exists( get_theme_file_path( 'templates/archive/archive-' . get_post_type() . '.php' ) ) ) {
										get_template_part( 'templates/archive/archive', get_post_type() );
									} else {
										get_template_part( 'templates/archive/archive', wpgen_options( 'archive_' . get_post_type() . '_template_type' ) );
									}
								?>

							</div>

						<?php endwhile; ?>

						</div>

					<?php endif; ?>

					<?php wp_reset_postdata(); ?>

			</section>

		<?php } ?>

	<?php }
}
