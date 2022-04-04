<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package wpgen
 */

if ( !defined( 'ABSPATH' ) ) exit;

//	Function get $output for site branding
//	Функция получает контент site branding
if ( !function_exists( 'get_wpgen_site_branding' ) ) {
	function get_wpgen_site_branding( $output = '' ) {

		$output = '<div class="site-branding">';
			//	Check if the image is set in the customizer settings or display the text
			//	Проверяем установлено ли изображение в настройках customizer или выводим текст
			if ( has_custom_logo() ) {
				$output .= get_custom_logo();
			} else {
				//	For all pages except the main page, display a link to it
				//	Для всех страниц кроме главной выводим ссылку на нее
				if ( ( is_front_page() || is_home() ) && !is_paged() ) {
					$output .= '<div class="site-branding__item">';
						$output .= '<strong class="site-branding__title">' . get_bloginfo( 'name' ) . '</strong>';
						$output .= '<p class="site-branding__description">' . get_bloginfo( 'description') . '</p>';
					$output .= '</div>';
				} else {
					$output .= '<a class="site-branding__item" href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
						$output .= '<strong class="site-branding__title">' . get_bloginfo( 'name' ) . '</strong>';
						$output .= '<p class="site-branding__description">' . get_bloginfo( 'description') . '</p>';
					$output .= '</a>';
				}
			}
		$output .= '</div>';

		return apply_filters( 'get_wpgen_site_branding', $output );
	}
}


//	Display content site branding with filters. Add support $before and $after
//	Выводим контент site branding с фильтрами. Добавляем поддержку $before и $after
if ( !function_exists( 'the_wpgen_site_branding' ) ) {
	function the_wpgen_site_branding( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_site_branding();

		$output = apply_filters( 'the_wpgen_site_branding', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo == true ) {
			echo $output;
		} else {
			return $output;
		}

	}
}


//	Function get $output for post meta information
//	Функция получает контент post meta information
if ( !function_exists( 'get_wpgen_post_meta_list' ) ) {
	function get_wpgen_post_meta_list( $output = '' ) {

		if ( wpgen_options( 'single_post_meta_author_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_autor">';
				$output .= '<a class="meta__link" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
			$output .= '</li>';
		}

		if ( wpgen_options( 'single_post_meta_date_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_date">';
				$output .= '<time data-title="' . __( 'Published Date', 'wpgen' ) . '" class="entry__date entry__date-published data-title" datetime="' . get_the_date( 'Y-m-d\TH:i:sP' ) . '">' . get_the_date( 'j M, Y' ) . '</time>';
				if ( wpgen_options( 'single_post_date_modified_display' ) == true && get_the_modified_date( 'j M, Y' ) != get_the_date( 'j M, Y' ) ) {
					$output .= '<time data-title="' . __( 'Modified Date', 'wpgen' ) . '" class="entry__date entry__date-modified small data-title" datetime="' . get_the_modified_date( 'Y-m-d\TH:i:sP' ) . '">(' . get_the_modified_date( 'j M, Y' ) . ')</time>';
				}
			$output .= '</li>';
		}

		if ( get_post_type() === 'post' ) {
			if ( wpgen_options( 'single_post_meta_cats_display' ) == true && has_category() ) {
				$output .= '<li class="meta__item meta__item_category">';
					$output .= get_the_category_list( ', ' );
				$output .= '</li>';
			}
			if ( wpgen_options( 'single_post_meta_tags_display' ) == true && has_tag() ) {
				$output .= '<li class="meta__item meta__item_tag">';
					$output .= get_the_tag_list( '', ', ' );
				$output .= '</li>';
			}
		}

		if ( wpgen_options( 'single_post_meta_comments_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_comments-count">';
				$output .= '<a class="meta__link" href="' . get_comments_link() . '" rel="bookmark">' . __( 'Comments', 'wpgen' ) . ': ' . get_comments_number() . '</a>';
			$output .= '</li>';
		}

		if ( get_post_meta( get_the_ID(), 'read_time', true ) == '' ) {
			add_post_meta( get_the_ID(), 'read_time', read_time_estimate( get_the_content() ), true );
		}

		if ( wpgen_options( 'single_post_meta_time_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_time data-title" data-title="' . __( 'Reading speed', 'wpgen' ) . '" >';
				$output .= get_post_meta( get_the_ID(), 'read_time', true ) . ' ' . __( 'min.', 'wpgen' );
			$output .= '</li>';
		}

		if ( is_plugin_active( 'kama-postviews/kama-postviews.php' ) && wpgen_options( 'single_post_meta_views_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_views-count data-title" data-title="' . __( 'Views', 'wpgen' ) . '" >';
				$output .= get_kap_views();
			$output .= '</li>';
		}

		if( wpgen_options( 'single_post_meta_edit_display' ) == true && current_user_can( 'edit_posts' ) ) {
			$output .= '<li class="meta__item meta__item_edit">';
				$output .= '<a class="edit-link" href="'. get_edit_post_link() .'">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</li>';
		}

		return apply_filters( 'get_wpgen_post_meta_list', $output );

	}
}

//	Display content post meta information with filters. Add support $before and $after
//	Выводим контент post meta information с фильтрами. Добавляем поддержку $before и $after
if ( !function_exists( 'the_wpgen_post_meta_list' ) ) {
	function the_wpgen_post_meta_list( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_post_meta_list();

		$output = apply_filters( 'the_wpgen_post_meta_list', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo == true ) {
			echo $output;
		} else {
			return $output;
		}

	}
}


//	Function get $output for archive meta information
//	Функция получает контент archive meta information 
if ( !function_exists( 'get_wpgen_archive_meta_list' ) ) {
	function get_wpgen_archive_meta_list( $output = '' ) {

		if ( wpgen_options( 'archive_page_meta_author_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_autor">';
				$output .= '<a class="meta__link" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
			$output .= '</li>';
		}

		if ( wpgen_options( 'archive_page_meta_date_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_date">';
				$output .= '<time data-title="' . __( 'Published Date', 'wpgen' ) . '" class="entry__date published data-title" datetime="' . get_the_date( 'Y-m-d\TH:i:sP' ) . '">' . get_the_date( 'j F, Y' ) . '</time>';
			$output .= '</li>';
		}

		if ( get_post_type() === 'post' ) {
			if ( wpgen_options( 'archive_page_meta_cats_display' ) == true && has_category() ) {
				$output .= '<li class="meta__item meta__item_category">';
					$output .= get_the_category_list( ', ' );
				$output .= '</li>';
			}
			if ( wpgen_options( 'archive_page_meta_tags_display' ) == true && has_tag() ) {
				$output .= '<li class="meta__item meta__item_tag">';
					$output .= get_the_tag_list( '', ', ' );
				$output .= '</li>';
			}
		}

		if ( wpgen_options( 'archive_page_meta_comments_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_comments-count">';
				$output .= '<a href="' . get_comments_link() . '" class="meta__link" rel="bookmark">' . __( 'Comments', 'wpgen' ) . ': ' . get_comments_number() . '</a>';
			$output .= '</li>';
		}

		if ( wpgen_options( 'archive_page_meta_time_display' ) == true && get_post_meta( get_the_ID(), 'read_time', true ) ) {
			$output .= '<li class="meta__item meta__item_time data-title" data-title="' . __( 'Reading speed', 'wpgen' ) . '" >';
				$output .= get_post_meta( get_the_ID(), 'read_time', true ) . ' ' . __( 'min.', 'wpgen' );
			$output .= '</li>';
		}

		if ( is_plugin_active( 'kama-postviews/kama-postviews.php' ) && wpgen_options( 'archive_page_meta_views_display' ) == true ) {
			$output .= '<li class="meta__item meta__item_views-count data-title" data-title="' . __( 'Views', 'wpgen' ) . '" >';
				if ( is_home() && is_front_page() ) {
					$output .= get_kap_views( get_the_ID(), 'post' ); // костыль из-за того, что в плагин не передается 'post'
				} else {
					$output .= get_kap_views();
				}
			$output .= '</li>';
		}

		if( wpgen_options( 'archive_page_meta_edit_display' ) == true && current_user_can( 'edit_posts' ) ) {
			$output .= '<li class="meta__item meta__item_edit">';
				$output .= '<a class="edit-link" href="'. get_edit_post_link() .'">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</li>';
		}

		return apply_filters( 'get_wpgen_archive_meta_list', $output );

	}
}

//	Display content archive meta information with filters. Add support $before and $after
//	Выводим контент archive meta information с фильтрами. Добавляем поддержку $before и $after
if ( !function_exists( 'the_wpgen_archive_meta_list' ) ) {
	function the_wpgen_archive_meta_list( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_archive_meta_list();

		$output = apply_filters( 'the_wpgen_archive_meta_list', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo == true ) {
			echo $output;
		} else {
			return $output;
		}

	}
}


// Function display the menu button
// Функция выводит кнопку меню
add_action( 'wpgen_before_main_navigation', 'wpgen_menu_toggle', 10 );
if ( !function_exists( 'wpgen_menu_toggle' ) ) {
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

		// изменяем тип кнопки, если в настройках выбран НЕ common
		if ( in_array( 'button', $classes ) && wpgen_options( 'general_button_type' ) !== 'common' ) {
			$classes[] = 'button-' . wpgen_options( 'general_button_type' );
		}

		// иконка справа/слева
		if ( in_array( $menu_button_type, ['button-icon-text', 'icon-text'] ) ) {
			$classes[] = 'menu-toggle_icon-' . wpgen_options( 'general_menu_button_icon_position' );
		}

		// вся кнопка справа/слева
		$classes[] = 'menu-toggle_' . wpgen_options( 'general_menu_button_alignment' );

		$output .= '<button id="menu-toggle" class="' . implode(' ', $classes) . '">';

		// пережиток прошлых версий
		//if ( $menu_button_type !== 'burger' ) $output .= '<span class="menu-toggle__text">' . __( 'Menu', 'wpgen' ) . '</span>';
		//if ( $menu_button_type === 'burger' || $menu_button_type === 'text-burger' ) $output .= '<span class="menu-toggle__icon"></span>';

		if ( !in_array( $menu_button_type, ['icon', 'button-icon'] ) ) {
			$output .= __( 'Menu', 'wpgen' );
		}

		$output .= '</button>';

		echo apply_filters( 'wpgen_menu_toggle', $output );

	}
}

// Testing
// Тестирование
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


// Function display scroll to top button
// Функция выводит кнопку скролла наверх
add_action( 'wp_footer_close', 'wpgen_scroll_top', 10 );
if ( !function_exists( 'wpgen_scroll_top' ) ) {
	function wpgen_scroll_top() {

		$output = '';

		if ( wpgen_options( 'general_scroll_top_button_display' ) === true ) {
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

			// изменяем тип кнопки, если в настройках выбран НЕ common
			if ( in_array( 'button', $classes ) && wpgen_options( 'general_button_type' ) !== 'common' ) {
				$classes[] = 'button-' . wpgen_options( 'general_button_type' );
			}

			// иконка справа/слева
			if ( in_array( $scroll_top_type, ['button-icon-text', 'icon-text'] ) ) {
				$classes[] = 'scroll-top_icon-' . wpgen_options( 'general_scroll_top_button_icon_position' );
			}

			// кнопка справа/слева
			$classes[] = 'scroll-top_' . wpgen_options( 'general_scroll_top_button_alignment' );

			$output .= '<button id="scroll-top" class="' . implode( ' ', $classes ) . '">';

				/*<div class="scroll-top <?php echo wpgen_options( 'general_scroll_top_position' ) === 'right' ? 'scroll-top_right' : 'scroll-top_left' ?>">
					<span class="scroll-top__text"><?php esc_html_e( 'Scroll up', 'wpgen' ); ?></span>
					<button class="button scroll-top__btn" type="button">
						<span class="scroll-top__btn-icon"></span>
					</button>
				</div>*/

				if ( !in_array( $scroll_top_type, ['icon', 'button-icon'] ) ) {
					$output .= __( 'Scroll up', 'wpgen' );
				}

			$output .= '</button>';
		}

		echo apply_filters( 'wpgen_scroll_top', $output );

	}
}

// Cookie Accepter
// Функция выводит блок принятия куки
add_action( 'wp_footer_close', 'wpgen_cookie_accepter', 20 );
if ( !function_exists( 'wpgen_cookie_accepter' ) ) {
	function wpgen_cookie_accepter() {

		$output = '';

		if ( !is_user_logged_in() && wpgen_options( 'general_cookie_display' ) === true ) {

			$privacy_policy_url = get_privacy_policy_url();

			if ( empty($privacy_policy_url) && is_multisite() && !is_main_site() ) {
				switch_to_blog( 1 );
				$privacy_policy_url = get_privacy_policy_url();
				restore_current_blog();
			}

			if ( empty($privacy_policy_url) ) $privacy_policy_url = get_home_url();

			$output .= '<div id="cookie" class="cookie" style="display: none">';
				$output .= '<div class="' . join( ' ', get_wpgen_container_classes() ) . '">';
					$output .= '<div class="row align-items-center">';
						$output .= '<div class="col-12 col-lg-8 cookie__message">';
							$output .= '<p class="small">' . __( 'We use cookies on our website to give you the most relevant experience by remembering your preferences and repeat visits. <br>By clicking «Accept», you consent to the use of ALL the cookies', 'wpgen' ) . '</p>';
						$output .= '</div>';
						$output .= '<div class="col-12 col-lg-2 cookie__privacy">';
							$output .= '<p><a href="' . $privacy_policy_url . '" role="button" class="link" tabindex="0">' . __( 'Cookie settings', 'wpgen' ) . '</a></p>';
						$output .= '</div>';
						$output .= '<div class="col-12 col-lg-2 cookie__confirm">';
							$output .= '<button id="cookie_action" class="button" type="button">' . __( 'Accept', 'wpgen' ) . '</button>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		}

		echo apply_filters( 'wpgen_cookie_accepter', $output );

	}
}






// Function display Breadcrumbs
// Функция выводит хлебные крошки
add_action( 'before_site_content', 'wpgen_breadcrumbs', 10 );
if ( !function_exists( 'wpgen_breadcrumbs' ) ) {
	function wpgen_breadcrumbs( $before = '', $after = '' ) {

		if ( !is_front_page() && !is_home() ) {

			if ( wpgen_options( 'general_breadcrumbs_display' ) == true ) {
				$before .= '<section id="breadcrumbs" class="site__breadcrumbs breadcrumbs breadcrumbs_' . wpgen_options( 'general_breadcrumbs' ) . '">';
					$before .= '<div class="' . join( ' ', get_wpgen_container_classes( ) ) . '">';
						$before .= '<div class="row">';
							$before .= '<div class="col-12 align-items-center">';
							
							$after .= '</div>';
						$after .= '</div>';
					$after .= '</div>';
				$after .= '</section>';


				if ( wpgen_options( 'general_breadcrumbs' ) == 'yoast' ) {
					if ( function_exists( 'yoast_breadcrumb' ) ) {
						echo $before;
							yoast_breadcrumb( '<nav class="breadcrumbs__navigation">', '</nav>' );
						echo $after;
					}
				} elseif( wpgen_options( 'general_breadcrumbs' ) == 'rankmath' ) {
					if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
						echo $before;
							rank_math_the_breadcrumbs();
						echo $after;
					}
				} elseif( wpgen_options( 'general_breadcrumbs' ) == 'seopress' ) {
					if ( function_exists( 'seopress_display_breadcrumbs' ) ) {
						echo $before;
							seopress_display_breadcrumbs();
						echo $after;
					}
				} else {
					if ( class_exists( 'WooCommerce' ) ) {
						echo $before;
							woocommerce_breadcrumb();
						echo $after;
					}
				}

			}

		}

	}
}


//	Function get $output for post navigation
//	Функция получает контент post navigation
if ( !function_exists( 'get_wpgen_post_navigation' ) ) {
	function get_wpgen_post_navigation( $output = '' ) {

		if ( true == wpgen_options( 'single_post_post_nav_display' ) && get_previous_post_link() && get_next_post_link() && is_single() ) {

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

			return apply_filters( 'get_wpgen_post_navigation', $output );

		}
	}
}


//	Display content post navigation with filters. Add support $before and $after
//	Выводим контент post navigation с фильтрами. Добавляем поддержку $before и $after
add_action( 'wpgen_before_comment_form', 'the_wpgen_post_navigation', 15 );
if ( !function_exists( 'the_wpgen_post_navigation' ) ) {
	function the_wpgen_post_navigation( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_post_navigation();

		$output = apply_filters( 'the_wpgen_post_navigation', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo == true ) {
			echo $output;
		} else {
			return $output;
		}
	}
}


//	Function get $output for archive navigation
//	Функция получает контент archive navigation
if ( !function_exists( 'get_wpgen_posts_navigation' ) ) {
	function get_wpgen_posts_navigation( $output = '' ) {

		global $paged;
		global $wp_query;
		$pages = $wp_query->max_num_pages;
		$range = 2;
		$showitems = ( $range * 2 ) + 1;
		$post_pagination = wpgen_options( 'archive_page_pagination' );

		if ( ! $pages || empty( $paged ) ) {
			$paged = 1;
		}

		if ( $pages == 1 ) {
			return;
		}

		$output .= '<nav class="navigation posts-navigation posts-navigation_' . esc_attr( $post_pagination ) . '" data-max-pages="' . esc_attr( $pages ) . '"  role="navigation" aria-label="' . esc_html__( 'Site post navigation', 'wpgen' ) . '" >';

		//	Choose numeric or older/newer pagination from customizer option
		//	Выбираем числовую или older/newer пагинацию из опций кастомайзера
		if ( $post_pagination === 'numeric' ) {

			//	Предыдущая страница
			//	Previous page
			if ( $paged > 1 ) {
				$output .= '<a href="'. esc_url( get_pagenum_link( $paged - 1 ) ) .'" class="posts-navigation__item posts-navigation__item_previous"></a>';
			}
			
			//	Основной цикл вывода ссылок
			//	Pagination
			for ( $i = 1; $i <= $pages; $i++ ) {
				if ( 1 != $pages &&( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
					if ( $paged == $i ) {
						$output .= '<span class="posts-navigation__item posts-navigation__item_current">'. $i .'</span>';
					} else {
						$output .= '<a href="'. esc_url( get_pagenum_link( $i ) ). '" class="posts-navigation__item">'. $i .'</a>';
					}
				}
			}

			//	Следующая страница
			//	Next Page
			if ( $paged < $pages ) {
				$output .= '<a href="'. esc_url( get_pagenum_link( $paged + 1 ) ).'" class="posts-navigation__item posts-navigation__item_next"></a>';
			}

		//	Пагинация по умолчанию
		//	Default Pagination
		} else {

			$output .= '<div class="row">';

			if ( get_next_posts_link() ) {
				$output .= '<div class="col-12 col-md-6">';
					$output .= '<div class="posts-navigation__item_previous">';
						next_posts_link( __( 'Older Posts', 'wpgen' ) );
					$output .= '</div>';
				$output .= '</div>';
			}
			
			if ( get_previous_posts_link() ) {
				$output .= '<div class="col-12 col-md-6">';
					$output .= '<div class="posts-navigation__item_next">';
						previous_posts_link( __( 'Newer Posts', 'wpgen' ) );
					$output .= '</div>';
				$output .= '</div>';
			}

			$output .= '</div>'; 
		}

		$output .= '</nav>';

		return apply_filters( 'get_wpgen_posts_navigation', $output );

		wp_reset_query();

	}
}

//	Display content archive navigation with filters. Add support $before and $after
//	Выводим контент archive navigation с фильтрами. Добавляем поддержку $before и $after
if ( !function_exists( 'the_wpgen_posts_navigation' ) ) {
	function the_wpgen_posts_navigation( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_posts_navigation();

		$output = apply_filters( 'the_wpgen_archive_meta_list', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo == true ) {
			echo $output;
		} else {
			return $output;
		}

	}
}

//	Function get $output for the pages, categories, tags and edit link
//	Функция получает контент для страниц, категорий, тегов и ссылки на редактирование
if ( !function_exists( 'get_wpgen_entry_footer' ) ) {
	function get_wpgen_entry_footer( $output = '' ) {
		//	Get page navigation links for multi-page posts (<!--nextpage--> is used for separation, one or more times in the content)
		//	Получаем ссылки навигации по страницам, для многостраничных постов (для разделения используется <!--nextpage-->, один или более раз в контенте)
		$output .= wp_link_pages( array(
			'before' => '<div class="article-footer__item pages-list">' . esc_html__( 'Pages:', 'wpgen' ),
			'after'  => '</div>',
			'echo'   => 0,
		) );

		// Get a list of categories, if the checkbox is set in the customizer settings and the post has category
		// Получаем список категорий, если установлен чекбокс в настройках customizer и они есть у поста
		if ( wpgen_options( 'single_post_entry_footer_cats_display' ) == true && has_category() ) {
			$categories_list = get_the_category_list();
			if ( $categories_list ) {
				$output .= '<div class="article-footer__item cats-list">';
					$output .= '<strong class="article-footer__title">' . esc_html__( 'Posted in:', 'wpgen' ) . '</strong>';
					$output .= $categories_list;
				$output .= '</div>';
			}
		}

		// Get a list of tags, if the checkbox is set in the customizer settings and the post has tag
		// Получаем список тегов, если установлен чекбокс в настройках customizer и они есть у поста
		if ( wpgen_options( 'single_post_entry_footer_tags_display' ) == true && has_tag() ) {
			$tags_list = get_the_tag_list('<li class="tag-list__item">', ', ', '</li>');
			if ( $tags_list ) {
				$output .= '<div class="article-footer__item tags-list">';
					$output .= '<strong class="article-footer__title">' . esc_html__( 'Tagged in:', 'wpgen' ) . '</strong>';
					$output .= '<ul class="post-tags">' . $tags_list . '</ul>';
				$output .= '</div>';
			}
		}

		// Get edit link, if the checkbox is set in the customizer settings and the user has enough rights
		// Получаем edit link, если установлен чекбокс в настройках customizer и у пользователя достаточно прав
		if( wpgen_options( 'single_post_meta_edit_display' ) == true && current_user_can( 'edit_posts' ) ) {
			$output .= '<div class="article-footer__item">';
				$output .= '<a class="edit-link" href="'. get_edit_post_link() .'">' . esc_html__( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</div>';
		}

		return apply_filters( 'get_wpgen_entry_footer', $output );
	}
}

//	Display content entry_footer with filters. Add support $before and $after
//	Выводим контент entry_footer с фильтрами. Добавляем поддержку $before и $after
if ( !function_exists( 'the_wpgen_entry_footer' ) ) {
	function the_wpgen_entry_footer( $before = '', $after = '', $echo = true ) {

		$output = get_wpgen_entry_footer();

		$output = apply_filters( 'the_wpgen_entry_footer', $output, $before, $after );

		$output = $before . $output . $after;

		if ( $echo == true ) {
			echo $output;
		} else {
			return $output;
		}

	}
}










//	Function display similar posts
//	Функция выводит похожие посты
add_action( 'wpgen_after_comment_form', 'the_wpgen_similar_posts', 15 );
if ( !function_exists( 'the_wpgen_similar_posts' ) ) {
	function the_wpgen_similar_posts() { ?>

		<?php if ( get_post_type() === 'post' && wpgen_options( 'single_post_similar_posts_display' ) == true  ) { ?>

		<section id="similar-posts" class="similar-posts">

			<h2 class="section-title"><?php _e( 'Similar posts', 'wpgen' ); ?></h2>

			<div class="row">

			<?php 

				$args = array(
					'post__not_in' => array( get_the_ID() ),
					'posts_per_page' => wpgen_options( 'single_post_similar_posts_count' ),
					'orderby' => wpgen_options( 'single_post_similar_posts_orderby' ),
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

				//vardump($args);

				$my_query = new wp_query($args);

				if( $my_query->have_posts() ) :
					while ($my_query->have_posts()) :
						$my_query->the_post(); ?>

						<div <?php wpgen_archive_page_columns_classes(); ?>>

							<?php if ( wpgen_options( 'archive_page_template_type' ) === 'custom' ) {
									do_action('wpgen_archive_page_template_custom');
								}	elseif ( wpgen_options( 'archive_page_template_type' ) === 'simple' ) {
									get_template_part( 'templates/archive/archive-simple');
								} elseif ( wpgen_options( 'archive_page_template_type' ) === 'banners' ) {
									get_template_part( 'templates/archive/archive-banners');
								} elseif ( wpgen_options( 'archive_page_template_type' ) === 'tils' ) {
									get_template_part( 'templates/archive/archive-tils');
								} else {
									get_template_part( 'templates/archive/archive-list');
								} ?>

						</div>

					<?php endwhile;
					
					endif;

				wp_reset_postdata(); ?>

			</div>

		</section>

		<?php } ?>

		<?php
	}
}

