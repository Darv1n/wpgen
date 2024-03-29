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

add_action( 'wpgen_before_main_navigation', 'wpgen_menu_toggle', 10 );
if ( ! function_exists( 'wpgen_menu_toggle' ) ) {

	/**
	 * Display the menu button on action hook wpgen_before_main_navigation.
	 */
	function wpgen_menu_toggle() {

		$output           = '';
		$classes          = array();
		$menu_button_type = wpgen_options( 'general_menu_button_type' );
		$button_type      = wpgen_options( 'general_button_type' );
		$color_scheme     = wpgen_options( 'general_color_scheme' );
		$button_color    = 'primary'; // default color. TODO: add filter?

		// Если есть кнопка.
		if ( in_array( $menu_button_type, array( 'button-icon-text', 'button-icon', 'button-text' ), true ) ) {
			$classes = get_button_classes( $classes, $button_color );
		} else {
			$classes[] = 'button-reset';
		}

		// Квадрат, если нет текста
		if ( in_array( $menu_button_type, array( 'button-icon', 'icon' ), true ) ) {
			$classes[] = 'button_squared';
		}

		// Если есть иконка.
		if ( in_array( $menu_button_type, array( 'button-icon-text', 'button-icon', 'icon-text', 'icon' ), true ) ) {
			$classes[] = 'toggle-icon';
			$classes[] = 'icon';
			$classes[] = 'icon_bars';

			if ( in_array( $menu_button_type, array( 'button-icon-text', 'icon-text' ), true ) ) {
				$classes[] = 'icon_' . wpgen_options( 'general_menu_button_icon_position' );
			}

			if ( in_array( $color_scheme, array( 'white', 'light' ), true ) && ( $button_type === 'empty' || ( $button_type === 'common' && in_array( $button_color, array( 'gray', 'default' ), true ) ) ) ) {
				$classes[] = 'icon_black';
			} else {
				$classes[] = 'icon_white';
			}
		}

		$classes[] = 'menu-toggle';

		if ( wpgen_options( 'general_header_type' ) === 'header-simple' ) {
			$classes[] = 'menu-toggle_right';
		} else {
			$classes[] = 'menu-toggle_' . wpgen_options( 'general_menu_button_alignment' );
		}

		$classes = apply_filters( 'get_menu_toggle_button_classes', $classes );
		$classes = array_unique( $classes );

		if ( in_array( 'toggle-icon', $classes, true ) ) {
			$output .= '<button id="menu-toggle" class="' . esc_attr( implode( ' ', $classes ) ) . '" data-icon-on="icon_xmark" data-icon-off="icon_bars">';
		} else {
			$output .= '<button id="menu-toggle" class="' . esc_attr( implode( ' ', $classes ) ) . '">';
		}
			if ( in_array( $menu_button_type, array( 'icon', 'button-icon' ), true ) ) {
				$output .= '<i class="icon"></i>';
			} else {
				$output .= __( 'Menu', 'wpgen' );
			}
		$output .= '</button>';

		// Filter html output.
		$output = apply_filters( 'wpgen_menu_toggle', $output );

		echo $output;
	}
}

// Usage: filter wpgen menu toggle.
// add_filter( 'wpgen_menu_toggle', 'custom_wpgen_menu_toggle', 10 );
if ( ! function_exists( 'custom_wpgen_menu_toggle' ) ) {
	function custom_wpgen_menu_toggle( $output ) {

		$output = '';

		$classes[] = 'menu-toggle';
		$menu_button_type = wpgen_options( 'general_menu_button_type' );

		if ( $menu_button_type === 'button' ) {
			$classes[] = 'button';
		}

		$output .= '<button id="menu-toggle hello" class="' . esc_attr( implode( ' ', $classes ) ) . '">';

			if ( $menu_button_type !== 'burger' ) {
				$output .= '<span class="menu-toggle__text">' . __( 'Menu', 'wpgen' ) . '</span>';
			}

			if ( $menu_button_type === 'burger' || $menu_button_type === 'text-burger' ) {
				$output .= '<span class="menu-toggle__icon"></span>';
			}

		$output .= '</button>';

		return $output;
	}
}

add_action( 'wp_footer_close', 'wpgen_scroll_top', 10 );
if ( ! function_exists( 'wpgen_scroll_top' ) ) {

	// Display scroll to top button on action hook wp_footer_close.
	function wpgen_scroll_top() {

		$output  = '';

		if ( wpgen_options( 'general_scroll_top_button_display' ) ) {
			$classes         = array();
			$scroll_top_type = wpgen_options( 'general_scroll_top_button_type' );
			$button_type     = wpgen_options( 'general_button_type' );
			$color_scheme    = wpgen_options( 'general_color_scheme' );
			$button_color    = 'primary'; // default color. TODO: add filter?

			// Если есть кнопка.
			if ( in_array( $scroll_top_type, array( 'button-icon-text', 'button-icon', 'button-text' ), true ) ) {
				$classes = get_button_classes( $classes, $button_color );
			} else {
				$classes[] = 'button-reset';
			}

			// Квадрат, если нет текста
			if ( in_array( $scroll_top_type, array( 'button-icon', 'icon' ), true ) ) {
				$classes[] = 'button_squared';
			}

			// Если есть иконка.
			if ( in_array( $scroll_top_type, array( 'button-icon-text', 'button-icon', 'icon-text', 'icon' ), true ) ) {
				$classes[] = 'toggle-icon';
				$classes[] = 'icon';
				$classes[] = 'icon_arrow-up';

				if ( in_array( $scroll_top_type, array( 'button-icon-text', 'icon-text' ), true ) ) {
					$classes[] = 'icon_' . wpgen_options( 'general_menu_button_icon_position' );
				}

				if ( in_array( $color_scheme, array( 'white', 'light' ), true ) && ( $button_type === 'empty' || ( $button_type === 'common' && in_array( $button_color, array( 'gray', 'default' ), true ) ) ) ) {
					$classes[] = 'icon_black';
				} else {
					$classes[] = 'icon_white';
				}
			}

			$classes[] = 'scroll-top';
			$classes[] = 'scroll-top_' . wpgen_options( 'general_scroll_top_button_alignment' );
			$classes   = apply_filters( 'get_scroll_top_button_classes', $classes );

			$output .= '<button id="scroll-top" class="' . esc_attr( implode( ' ', $classes ) ) . '">';
				if ( in_array( $scroll_top_type, array( 'icon', 'button-icon' ), true ) ) {
					$output .= '<i class="icon"></i>';
				} else {
					$output .= __( 'Scroll up', 'wpgen' );
				}
			$output .= '</button>';
		}

		// Filter html output.
		$output = apply_filters( 'wpgen_scroll_top', $output );

		echo $output;
	}
}

add_action( 'wp_footer_close', 'wpgen_cookie_accepter', 20 );
if ( ! function_exists( 'wpgen_cookie_accepter' ) ) {

	/**
	 * Display cookie accepter on action hook wp_footer_close.
	 */
	function wpgen_cookie_accepter() {

		$home_parse_url = wp_parse_url( get_home_url() );
		$output         = '';

		if ( ! is_user_logged_in() && wpgen_options( 'general_cookie_display' ) ) {
			$output .= '<div id="cookie" class="cookie" style="display: none">';
				$output .= '<div class="' . esc_attr( implode( ' ', get_wpgen_container_classes() ) ) . '">';
					$output .= sprintf( wp_kses( '<p class="cookie__text">' . __( 'By continuing to use %s, you agree to the use of cookies. More information can be found in the <a class="%s" href="%s" target="_blank">Privacy Policy</a>' .  '</p>', 'wpgen' ), kses_available_tags() ), $home_parse_url['host'], esc_attr( implode( ' ', get_link_classes() ) ), esc_url( get_privacy_policy_url() ) );
					$output .= '<span id="cookie-action" class="cookie__action" role="button"><i class="icon icon_xmark"></i></span>';
				$output .= '</div>';
			$output .= '</div>';
		}

		// Filter html output.
		$output = apply_filters( 'wpgen_cookie_accepter', $output );

		echo $output;
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
			$output = apply_filters( 'get_wpgen_post_navigation', $output );

			return $output;
		}
	}
}

add_action( 'wpgen_after_single_content_part', 'the_wpgen_post_navigation', 15 );
if ( ! function_exists( 'the_wpgen_post_navigation' ) ) {

	/**
	 * Display content post navigation with filters on action hook wpgen_before_comment_form.
	 *
	 * @param string $before HTML before post navigation.
	 * @param string $after  HTML after post navigation.
	 * @param bool   $echo   Echo or return html output.
	 *
	 * @return string|void
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

		$output .= '<nav class="navigation posts-navigation posts-navigation_' . esc_attr( $post_pagination ) . '" data-max-pages="' . esc_attr( $pages ) . '" role="navigation" aria-label="' . __( 'Site post navigation', 'wpgen' ) . '">';

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
							$classes = 'button-small button-disabled posts-navigation__item posts-navigation__item_current';
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

		wp_reset_postdata();

		// Filter html output.
		$output = apply_filters( 'get_wpgen_posts_navigation', $output );

		return $output;
	}
}

if ( ! function_exists( 'the_wpgen_posts_navigation' ) ) {

	/**
	 * Display content archive posts navigation with filters.
	 *
	 * @param string $before HTML before posts navigation.
	 * @param string $after  HTML after posts navigation.
	 * @param bool   $echo   Echo or return html output.
	 *
	 * @return string|void
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



add_action( 'wpgen_after_single_content_part', 'the_wpgen_similar_posts', 25 );
if ( ! function_exists( 'the_wpgen_similar_posts' ) ) {

	/**
	 * Display similar posts on action hook wpgen_after_comment_form.
	 */
	function the_wpgen_similar_posts() { ?>

		<?php if ( get_post_type() === 'post' && wpgen_options( 'single_post_similar_posts_display' ) ) { ?>

			<section id="similar-posts" class="section section_similar-posts similar-posts" aria-label="<?php _e( 'Similar posts', 'wpgen' ); ?>">

				<h2 class="section-title"><?php _e( 'Similar posts', 'wpgen' ); ?></h2>

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
