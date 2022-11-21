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

		$output = '<div class="site-branding">';

		// Check if the image is set in the customizer settings or display the text.
		if ( has_custom_logo() ) {
			$output .= get_custom_logo();
		} else {
			// For all pages except the main page, display a link to it.
			if ( ( is_front_page() || is_home() ) && ! is_paged() ) {
				$output .= '<div class="site-branding__item">';
					$output .= '<strong class="site-branding__title">' . get_bloginfo( 'name' ) . '</strong>';
					$output .= '<p class="site-branding__description">' . get_bloginfo( 'description' ) . '</p>';
				$output .= '</div>';
			} else {
				$output .= '<a class="site-branding__item" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
					$output .= '<strong class="site-branding__title">' . get_bloginfo( 'name' ) . '</strong>';
					$output .= '<p class="site-branding__description">' . get_bloginfo( 'description' ) . '</p>';
				$output .= '</a>';
			}
		}

		$output .= '</div>';

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

		if ( wpgen_options( 'single_post_meta_author_display' ) ) {
			$output .= '<li class="meta__item meta__item_autor">';
				$output .= '<a class="meta__link" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
			$output .= '</li>';
		}

		if ( wpgen_options( 'single_post_meta_date_display' ) ) {
			$output .= '<li class="meta__item meta__item_date">';
				$output .= '<time data-title="' . esc_html__( 'Published Date', 'wpgen' ) . '" class="entry__date entry__date-published data-title" datetime="' . get_the_date( 'Y-m-d\TH:i:sP' ) . '">' . get_the_date( 'j M, Y' ) . '</time>';
				if ( wpgen_options( 'single_post_date_modified_display' ) && get_the_modified_date( 'j M, Y' ) !== get_the_date( 'j M, Y' ) ) {
					$output .= '<time data-title="' . esc_html__( 'Modified Date', 'wpgen' ) . '" class="entry__date entry__date-modified small data-title" datetime="' . get_the_modified_date( 'Y-m-d\TH:i:sP' ) . '">(' . get_the_modified_date( 'j M, Y' ) . ')</time>';
				}
			$output .= '</li>';
		}

		if ( get_post_type() === 'post' ) {
			if ( wpgen_options( 'single_post_meta_cats_display' ) && has_category() ) {
				$output .= '<li class="meta__item meta__item_category">';
					$output .= get_the_category_list( ', ' );
				$output .= '</li>';
			}
			if ( wpgen_options( 'single_post_meta_tags_display' ) && has_tag() ) {
				$output .= '<li class="meta__item meta__item_tag">';
					$output .= get_the_tag_list( '', ', ' );
				$output .= '</li>';
			}
		}

		if ( wpgen_options( 'single_post_meta_comments_display' ) ) {
			$output .= '<li class="meta__item meta__item_comments-count">';
				$output .= '<a class="meta__link" href="' . esc_url( get_comments_link() ) . '" rel="bookmark">' . esc_html__( 'Comments', 'wpgen' ) . ': ' . get_comments_number() . '</a>';
			$output .= '</li>';
		}

		if ( empty( get_post_meta( get_the_ID(), 'read_time', true ) ) ) {
			add_post_meta( get_the_ID(), 'read_time', read_time_estimate( get_the_content() ), true );
		}

		if ( wpgen_options( 'single_post_meta_time_display' ) ) {
			$output .= '<li class="meta__item meta__item_time data-title" data-title="' . esc_html__( 'Reading speed', 'wpgen' ) . '" >';
				$output .= get_post_meta( get_the_ID(), 'read_time', true ) . ' ' . esc_html__( 'min.', 'wpgen' );
			$output .= '</li>';
		}

		if ( is_plugin_active( 'kama-postviews/kama-postviews.php' ) && wpgen_options( 'single_post_meta_views_display' ) ) {
			$output .= '<li class="meta__item meta__item_views-count data-title" data-title="' . esc_html__( 'Views', 'wpgen' ) . '" >';
				$output .= get_kap_views();
			$output .= '</li>';
		}

		if ( wpgen_options( 'single_post_meta_edit_display' ) && current_user_can( 'edit_posts' ) ) {
			$output .= '<li class="meta__item meta__item_edit">';
				$output .= '<a class="edit-link" href="' . esc_url( get_edit_post_link() ) . '">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</li>';
		}

		// Filter html output.
		return apply_filters( 'get_wpgen_post_meta_list', $output );
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

		if ( wpgen_options( 'archive_page_meta_author_display' ) ) {
			$output .= '<li class="meta__item meta__item_autor">';
				$output .= '<a class="meta__link" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
			$output .= '</li>';
		}

		if ( wpgen_options( 'archive_page_meta_date_display' ) ) {
			$output .= '<li class="meta__item meta__item_date">';
				$output .= '<time data-title="' . esc_html__( 'Published Date', 'wpgen' ) . '" class="entry__date published data-title" datetime="' . get_the_date( 'Y-m-d\TH:i:sP' ) . '">' . get_the_date( 'j F, Y' ) . '</time>';
			$output .= '</li>';
		}

		if ( get_post_type() === 'post' ) {
			if ( wpgen_options( 'archive_page_meta_cats_display' ) && has_category() ) {
				$output .= '<li class="meta__item meta__item_category">';
					$output .= get_the_category_list( ', ' );
				$output .= '</li>';
			}
			if ( wpgen_options( 'archive_page_meta_tags_display' ) && has_tag() ) {
				$output .= '<li class="meta__item meta__item_tag">';
					$output .= get_the_tag_list( '', ', ' );
				$output .= '</li>';
			}
		}

		if ( wpgen_options( 'archive_page_meta_comments_display' ) ) {
			$output .= '<li class="meta__item meta__item_comments-count">';
				$output .= '<a href="' . get_comments_link() . '" class="meta__link" rel="bookmark">' . esc_html__( 'Comments', 'wpgen' ) . ': ' . get_comments_number() . '</a>';
			$output .= '</li>';
		}

		if ( wpgen_options( 'archive_page_meta_time_display' ) && get_post_meta( get_the_ID(), 'read_time', true ) ) {
			$output .= '<li class="meta__item meta__item_time data-title" data-title="' . esc_html__( 'Reading speed', 'wpgen' ) . '" >';
				$output .= get_post_meta( get_the_ID(), 'read_time', true ) . ' ' . esc_html__( 'min.', 'wpgen' );
			$output .= '</li>';
		}

		if ( is_plugin_active( 'kama-postviews/kama-postviews.php' ) && wpgen_options( 'archive_page_meta_views_display' ) ) {
			$output .= '<li class="meta__item meta__item_views-count data-title" data-title="' . esc_html__( 'Views', 'wpgen' ) . '" >';
				if ( is_home() && is_front_page() ) {
					$output .= get_kap_views( get_the_ID(), 'post' ); // костыль из-за того, что в плагин не передается 'post'.
				} else {
					$output .= get_kap_views();
				}
			$output .= '</li>';
		}

		if ( wpgen_options( 'archive_page_meta_edit_display' ) && current_user_can( 'edit_posts' ) ) {
			$output .= '<li class="meta__item meta__item_edit">';
				$output .= '<a class="edit-link" href="' . esc_url( get_edit_post_link() ) . '">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</li>';
		}

		// Filter html output.
		return apply_filters( 'get_wpgen_archive_meta_list', $output );
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



add_action( 'wpgen_before_main_navigation', 'wpgen_menu_toggle', 10 );
if ( ! function_exists( 'wpgen_menu_toggle' ) ) {

	/**
	 * Display the menu button on action hook wpgen_before_main_navigation.
	 */
	function wpgen_menu_toggle() {

		$output = '';

		$classes[] = 'menu-toggle';

		$menu_button_type = wpgen_options( 'general_menu_button_type' );

		if ( $menu_button_type === 'button-icon-text' ) {
			$classes[] = 'button';
			$classes[] = 'menu-toggle_button';
			$classes[] = 'menu-toggle_icon';
		} elseif ( $menu_button_type === 'button-icon' ) {
			$classes[] = 'button';
			$classes[] = 'menu-toggle_button';
			$classes[] = 'menu-toggle_icon';
			$classes[] = 'menu-toggle_solo-icon';
		} elseif ( $menu_button_type === 'button-text' ) {
			$classes[] = 'button';
			$classes[] = 'menu-toggle_button';
		} elseif ( $menu_button_type === 'icon' ) {
			$classes[] = 'btn-reset';
			$classes[] = 'menu-toggle_icon';
			$classes[] = 'menu-toggle_solo-icon';
		} elseif ( $menu_button_type === 'icon-text' ) {
			$classes[] = 'btn-reset';
			$classes[] = 'menu-toggle_icon';
			$classes[] = 'menu-toggle_text';
		} else {
			$classes[] = 'btn-reset';
			$classes[] = 'menu-toggle_text';
		}

		// изменяем тип кнопки, если в настройках выбран НЕ common.
		if ( in_array( 'button', $classes, true ) && wpgen_options( 'general_button_type' ) !== 'common' ) {
			$classes[] = 'button-' . wpgen_options( 'general_button_type' );
		}

		// иконка справа/слева.
		if ( in_array( $menu_button_type, array( 'button-icon-text', 'icon-text' ), true ) ) {
			$classes[] = 'menu-toggle_icon-' . wpgen_options( 'general_menu_button_icon_position' );
		}

		// вся кнопка справа/слева.
		$classes[] = 'menu-toggle_' . wpgen_options( 'general_menu_button_alignment' );

		$output .= '<button id="menu-toggle" class="' . esc_attr( implode( ' ', $classes ) ) . '">';

		if ( ! in_array( $menu_button_type, array( 'icon', 'button-icon' ), true ) ) {
			$output .= esc_html__( 'Menu', 'wpgen' );
		}

		$output .= '</button>';

		// Filter html output.
		echo apply_filters( 'wpgen_menu_toggle', $output );
	}
}

// Testing.
/*add_filter( 'wpgen_menu_toggle','my_menu_toggle' );
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
}*/

add_action( 'wp_footer_close', 'wpgen_scroll_top', 10 );
if ( ! function_exists( 'wpgen_scroll_top' ) ) {

	// Display scroll to top button on action hook wp_footer_close.
	function wpgen_scroll_top() {

		$output = '';

		if ( wpgen_options( 'general_scroll_top_button_display' ) ) {
			$classes[] = 'scroll-top';

			$scroll_top_type = wpgen_options( 'general_scroll_top_button_type' );
			if ( $scroll_top_type === 'button-icon-text' ) {
				$classes[] = 'button';
				$classes[] = 'scroll-top_button';
				$classes[] = 'scroll-top_icon';
			} elseif ( $scroll_top_type === 'button-icon' ) {
				$classes[] = 'button';
				$classes[] = 'scroll-top_button';
				$classes[] = 'scroll-top_icon';
				$classes[] = 'scroll-top_solo-icon';
			} elseif ( $scroll_top_type === 'button-text' ) {
				$classes[] = 'button';
				$classes[] = 'scroll-top_button';
			} elseif ( $scroll_top_type === 'icon' ) {
				$classes[] = 'btn-reset';
				$classes[] = 'scroll-top_icon';
				$classes[] = 'scroll-top_solo-icon';
			} elseif ( $scroll_top_type === 'icon-text' ) {
				$classes[] = 'btn-reset';
				$classes[] = 'scroll-top_icon';
				$classes[] = 'scroll-top_text';
			} else {
				$classes[] = 'btn-reset';
				$classes[] = 'scroll-top_text';
			}

			// изменяем тип кнопки, если в настройках выбран НЕ common.
			if ( in_array( 'button', $classes, true ) && wpgen_options( 'general_button_type' ) !== 'common' ) {
				$classes[] = 'button-' . wpgen_options( 'general_button_type' );
			}

			// иконка справа/слева.
			if ( in_array( $scroll_top_type, array( 'button-icon-text', 'icon-text' ), true ) ) {
				$classes[] = 'scroll-top_icon-' . wpgen_options( 'general_scroll_top_button_icon_position' );
			}

			// кнопка справа/слева.
			$classes[] = 'scroll-top_' . wpgen_options( 'general_scroll_top_button_alignment' );

			$output .= '<button id="scroll-top" class="' . esc_attr( implode( ' ', $classes ) ) . '">';

				if ( ! in_array( $scroll_top_type, array( 'icon', 'button-icon' ), true ) ) {
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

			$privacy_policy_url = get_privacy_policy_url();

			if ( empty( $privacy_policy_url ) && is_multisite() && ! is_main_site() ) {
				switch_to_blog( 1 );
				$privacy_policy_url = get_privacy_policy_url();
				restore_current_blog();
			}

			if ( empty( $privacy_policy_url ) ) {
				$privacy_policy_url = get_home_url();
			}

			$output .= '<div id="cookie" class="cookie" style="display: none">';
				$output .= '<div class="' . esc_attr( implode( ' ', get_wpgen_container_classes() ) ) . '">';
					$output .= '<div class="row align-items-center">';
						$output .= '<div class="col-12 col-lg-8 cookie__message">';
							$output .= '<p class="small">' . __( 'We use cookies on our website to give you the most relevant experience by remembering your preferences and repeat visits. <br>By clicking «Accept», you consent to the use of ALL the cookies', 'wpgen' ) . '</p>';
						$output .= '</div>';
						$output .= '<div class="col-12 col-lg-2 cookie__privacy">';
							$output .= '<p><a href="' . esc_url( $privacy_policy_url ) . '" role="button" class="link link-color-unborder" tabindex="0">' . esc_html__( 'Cookie settings', 'wpgen' ) . '</a></p>';
						$output .= '</div>';
						$output .= '<div class="col-12 col-lg-2 cookie__confirm">';
							$output .= '<button id="cookie_action" class="' . esc_attr( implode( ' ', get_button_classes() ) ) . '" type="button">' . esc_html__( 'Accept', 'wpgen' ) . '</button>';
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
	 *
	 * @param string $before HTML before breadcrumbs.
	 * @param string $after  HTML after breadcrumbs.
	 */
	function wpgen_breadcrumbs( $before = '', $after = '' ) {

		if ( ! is_front_page() && ! is_home() ) {

			if ( wpgen_options( 'general_breadcrumbs_display' ) ) {
				$before .= '<section id="breadcrumbs" class="site__breadcrumbs breadcrumbs breadcrumbs_' . esc_attr( wpgen_options( 'general_breadcrumbs' ) ) . '">';
					$before .= '<div class="' . esc_attr( implode( ' ', get_wpgen_container_classes() ) ) . '">';
						$before .= '<div class="row">';
							$before .= '<div class="col-12 align-items-center">';

							$after .= '</div>';
						$after .= '</div>';
					$after .= '</div>';
				$after .= '</section>';

				if ( wpgen_options( 'general_breadcrumbs' ) === 'yoast' && function_exists( 'yoast_breadcrumb' ) ) {
					echo $before;
						yoast_breadcrumb( '<nav class="breadcrumbs__navigation">', '</nav>' );
					echo $after;
				} elseif ( wpgen_options( 'general_breadcrumbs' ) === 'rankmath' && function_exists( 'rank_math_the_breadcrumbs' ) ) {
					echo $before;
						rank_math_the_breadcrumbs();
					echo $after;
				} elseif ( wpgen_options( 'general_breadcrumbs' ) === 'seopress' && function_exists( 'seopress_display_breadcrumbs' ) ) {
					echo $before;
						seopress_display_breadcrumbs();
					echo $after;
				} elseif ( class_exists( 'WooCommerce' ) ) {
					echo $before;
						woocommerce_breadcrumb();
					echo $after;
				}
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

		if ( wpgen_options( 'single_post_post_nav_display' ) && get_previous_post_link() && get_next_post_link() && is_single() ) {

			$output .= '<nav class="navigation post-navigation" role="navigation">';
				$output .= '<div class="row">';
					$output .= '<div class="col-12 col-md-6">';
						$output .= get_previous_post_link( '<div class="post-navigation__item post-navigation__item_previous">%link</div>', '<span class="entry__title">← %title</span>' );
					$output .= '</div>';
					$output .= '<div class="col-12 col-md-6">';
						$output .= get_next_post_link( '<div class="post-navigation__item post-navigation__item_next">%link</div>', '<span class="entry__title">%title →</span>' );
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
		$post_pagination = wpgen_options( 'archive_page_pagination' );

		if ( ! $pages || empty( $paged ) ) {
			$paged = 1;
		}

		if ( (int) $pages === 1 ) {
			return;
		}

		$output .= '<nav class="navigation posts-navigation posts-navigation_' . esc_attr( $post_pagination ) . '" data-max-pages="' . esc_attr( $pages ) . '" role="navigation" aria-label="' . esc_html__( 'Site post navigation', 'wpgen' ) . '">';

		// Choose numeric or older/newer pagination from customizer option.
		if ( $post_pagination === 'numeric' ) {

			// Previous page.
			if ( $paged > 3 ) {
				$output .= '<a href="' . esc_url( get_pagenum_link( 1 ) ) . '" class="posts-navigation__item button posts-navigation__item_prev">-1</a>';
			}

			// Основной цикл вывода ссылок.
			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					if ( $paged === $i ) {
						$output .= '<span class="posts-navigation__item posts-navigation__item_current button button-default disabled">' . $i . '</span>';
					} else {
						$output .= '<a href="' . esc_url( get_pagenum_link( $i ) ) . '" class="posts-navigation__item button">' . $i . '</a>';
					}
				}
			}

			// Next Page.
			if ( $pages > 5 && $paged < $pages - 2 ) {
				$output .= '<a href="' . esc_url( get_pagenum_link( $pages ) ) . '" class="posts-navigation__item button posts-navigation__item_next">+1</a>';
			}

		} else {

			// Default Pagination.
			$output .= '<div class="row">';

			if ( get_next_posts_link() ) {
				$output .= '<div class="col-12 col-md-6">';
					$output .= '<div class="posts-navigation__item_previous">';
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
			'before' => '<div class="article-footer__item pages-list">' . esc_html__( 'Pages:', 'wpgen' ),
			'after'  => '</div>',
			'echo'   => 0,
		) );

		// Get a list of categories, if the checkbox is set in the customizer settings and the post has category.
		if ( wpgen_options( 'single_post_entry_footer_cats_display' ) && has_category() ) {
			$categories_list = get_the_category_list();
			if ( $categories_list ) {
				$output .= '<div class="article-footer__item cats-list">';
					$output .= '<strong class="article-footer__title">' . esc_html__( 'Posted in:', 'wpgen' ) . '</strong>';
					$output .= $categories_list;
				$output .= '</div>';
			}
		}

		// Get a list of tags, if the checkbox is set in the customizer settings and the post has tag.
		if ( wpgen_options( 'single_post_entry_footer_tags_display' ) && has_tag() ) {
			$tags_list = get_the_tag_list( '<li class="tag-list__item">', ', ', '</li>' );
			if ( $tags_list ) {
				$output .= '<div class="article-footer__item tags-list">';
					$output .= '<strong class="article-footer__title">' . esc_html__( 'Tagged in:', 'wpgen' ) . '</strong>';
					$output .= '<ul class="post-tags">' . $tags_list . '</ul>';
				$output .= '</div>';
			}
		}

		// Get edit link, if the checkbox is set in the customizer settings and the user has enough rights.
		if ( wpgen_options( 'single_post_meta_edit_display' ) && current_user_can( 'edit_posts' ) ) {
			$output .= '<div class="article-footer__item">';
				$output .= '<a class="edit-link link link-color-unborder" href="' . esc_url( get_edit_post_link() ) . '">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
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

		<section id="similar-posts" class="similar-posts">

			<h2 class="section-title"><?php esc_html_e( 'Similar posts', 'wpgen' ); ?></h2>

			<div class="row">

			<?php

				$args = array(
					'post__not_in'   => array( get_the_ID() ),
					'posts_per_page' => wpgen_options( 'single_post_similar_posts_count' ),
					'orderby'        => wpgen_options( 'single_post_similar_posts_orderby' ),
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
					<?php while ( $query->have_posts() ) : ?>
						<?php $query->the_post(); ?>

						<div <?php wpgen_archive_page_columns_classes(); ?>>

							<?php

								if ( wpgen_options( 'archive_page_template_type' ) === 'custom' ) {
									do_action('wpgen_archive_page_template_custom' );
								} else {
									get_template_part( 'templates/archive/archive', wpgen_options( 'archive_page_template_type' ) );
								}

							?>

						</div>

					<?php endwhile; ?>

					<?php endif; ?>

				<?php wp_reset_postdata(); ?>

			</div>

		</section>

		<?php } ?>

		<?php
	}
}
