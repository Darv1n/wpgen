<?php
/**
 * Deprecated functions.
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
		$output = apply_filters( 'get_wpgen_site_branding', $output );

		return $output;
	}
}

if ( ! function_exists( 'the_wpgen_site_branding' ) ) {

	/**
	 * Display $output for site branding.
	 *
	 * @param string $before HTML before site branding.
	 * @param string $after  HTML after site branding.
	 * @param bool   $echo   Echo or return html output.
	 *
	 * @return string|void
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
			'before' => '<div class="post-footer__item post-footer__pages">' . __( 'Pages:', 'wpgen' ),
			'after'  => '</div>',
			'echo'   => 0,
		) );

		if ( get_post_type() === 'post' ) {
			if ( wpgen_options( 'single_' . get_post_type() . '_entry_footer_cats_display' ) && has_category() ) {
				$output .= '<div class="post-footer__item post-footer__cats">';
					$output .= '<strong>' . __( 'Post categories', 'wpgen' ) . ':</strong>';
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
					$output .= '<strong>' . __( 'Post tags', 'wpgen' ) . ':</strong>';
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
				$output .= '<a class="' . esc_attr( implode( ' ', get_link_classes( 'edit-link' ) ) ) . '" href="' . esc_url( get_edit_post_link() ) . '">' . __( 'Edit', 'wpgen' ) . '</a>';
			$output .= '</div>';
		}

		// Filter html output.
		$output = apply_filters( 'get_wpgen_entry_footer', $output );

		return $output;
	}
}

if ( ! function_exists( 'the_wpgen_entry_footer' ) ) {

	/**
	 * Display content entry_footer with filters.
	 *
	 * @param string $before HTML before content entry_footer.
	 * @param string $after  HTML after content entry_footer.
	 * @param bool   $echo   Echo or return html output.
	 *
	 * @return string|void
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

if ( ! function_exists( 'translit' ) ) {

	/**
	 * String transliteration function.
	 *
	 * @param string $control array key to get one value.
	 *
	 * @return array
	 */
	function translit( $control = null ) {

		$converter = array(
			'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
			'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
			'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
			'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
			'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
			'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
			'э' => 'e',    'ю' => 'yu',   'я' => 'ya',

			'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
			'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
			'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
			'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
			'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
			'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
			'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
		);

		// Return controls.
		if ( is_null( $control ) ) {
			return $converter;
		} else {
			return strtr( $control, $converter );;
		}
	}
}


if ( ! function_exists( 'get_post_nesting_level' ) ) {

	/**
	 * Returns the nesting post level of type like 'page'.
	 *
	 * @param int $post_id Post ID. Default = global $post.
	 *
	 * @return int
	 */
	function get_post_nesting_level( $post_id = null ) {

		if ( is_null( $page_id ) ) {
			global $post;
			$post_id = $post->ID;
		}

		$ancestors = get_ancestors( $post_id, get_post_type() );

		if ( ! is_array( $ancestors ) ) {
			return false;
		}

		return count( $ancestors );
	}
}

if ( ! function_exists( 'get_nesting_level' ) ) {

	/**
	 * Returns the nesting term level.
	 *
	 * @param int $object_id     ID.
	 * @param int $object_type   category, page, post.
	 * @param int $resource_type post_type or taxonomy.
	 *
	 * @return int
	 */
	function get_nesting_level( $object_id = null, $object_type = null, $resource_type = '' ) {

		if ( is_null( $object_id ) || is_null( $object_type ) ) {
			return false;
		}

		$ancestors = get_ancestors( $object_id, $object_type, $resource_type );

		if ( ! is_array( $ancestors ) ) {
			return false;
		}

		return count( $ancestors );
	}
}

if ( ! function_exists( 'get_probability' ) ) {

	/**
	 * Gets the random probability of the event.
	 *
	 * @param int $percent source input percentage.
	 *
	 * @return bool
	 */
	function get_probability( $percent = 50 ) {

		$probability = wp_rand( 0, 100 );

		if ( $percent >= $probability ) {
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'get_webp_from_folders' ) ) {

	/**
	 * Outputs webp picture HTML.
	 *
	 * @param string $name        source image name.
	 * @param string $folder      base image folder.
	 * @param string $folder_webp base image folder webps.
	 * @param string $alt         string for alt/title attribute.
	 * @param string $class       classes for image container.
	 * @param string $before      HTML before output.
	 * @param string $after       HTML after output.
	 *
	 * @return string
	 */
	function get_webp_from_folders( $name, $folder, $folder_webp, $alt = '', $class = 'aspect-ratio' ) {

		$output = '';

		// Get parts of image.
		$ext = pathinfo( $name );

		// Collect file paths.
		$path      = $folder . $ext['basename'];
		$path_webp = $folder_webp . $ext['filename'] . '.webp';

		// Check that image exists.
		if ( file_exists( get_stylesheet_directory() . $path ) ) {

			// Get height and width of the image.
			$image_attributes = getimagesize( get_stylesheet_directory() . $path );

			// Check that the .webp image exists.
			if ( file_exists( get_stylesheet_directory() . $path_webp ) ) {
				$output .= '<picture class="' . $class . '">';
					$output .= '<source srcset="' . get_stylesheet_directory_uri() . $path_webp . '" type="image/webp">';
					$output .= '<source srcset="' . get_stylesheet_directory_uri() . $path . '" type="' . $image_attributes['mime'] . '">';
					$output .= '<img src="' . get_stylesheet_directory_uri() . $path . '" alt="' . $alt . '" ' . $image_attributes[3] . ' loading="lazy">';
				$output .= '</picture>';
			} else {
				$output .= '<div class="' . $class . '">';
					$output .= '<img src="' . get_stylesheet_directory_uri() . $path . '" alt="' . $alt . '" ' . $image_attributes[3] . ' loading="lazy">';
				$output .= '</div>';
			}
		}

		return $output;
	}
}