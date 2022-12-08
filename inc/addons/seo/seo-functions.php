<?php
/**
 * SEO functions.
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_wpgen_seo_meta' ) ) {

	/**
	 * Checks print or not to display seo tags.
	 *
	 * @return bool
	 */
	function is_wpgen_seo_meta() {

		if ( ! wpgen_options( 'general_seo_tags_display' ) ) {
			return false;
		}

		$seo_plagins = array(
			'wordpress-seo/wp-seo.php',
			'autodescription/autodescription.php',
			'seo-by-rank-math/rank-math.php',
			'wp-seopress/seopress.php',
		);

		foreach ( $seo_plagins as $key => $seo_plagin ) {
			if ( is_plugin_active( $seo_plagin ) ) {
				return false;
			}
		}

		return true;
	}
}

if ( ! function_exists( 'get_wpgen_title_separator' ) ) {

	/**
	 * Output title separator.
	 *
	 * @return string
	 */
	function get_wpgen_title_separator() {
		return '|';
	}
}

if ( ! function_exists( 'get_wpgen_seo_meta_data' ) ) {

	/**
	 * Return array with seo meta tags array.
	 *
	 * @param string $control array key to get one value.
	 *
	 * @return array
	 */
	function get_wpgen_seo_meta_data( $control = null ) {

		global $wp;
		global $paged;
		global $wp_query;

		$seo_defaults = array();
		$query_args   = array(); // массив аргументов для тега canonical.
		$separator    = get_wpgen_title_separator();

		$allowed_seo_canonical_query_vars = array(
			'p',
		);

		// Merge child and parent default options.
		$allowed_seo_canonical_query_vars = apply_filters( 'allowed_seo_canonical_query_vars', $allowed_seo_canonical_query_vars );

		/*
		// Usage:
		add_filter( 'allowed_seo_canonical_query_vars', 'source_allowed_seo_canonical_query_vars' );
		function source_allowed_seo_canonical_query_vars( $allowed_seo_canonical_query_vars ) {

			$allowed_seo_canonical_query_vars[] = 'pg';

			return $allowed_seo_canonical_query_vars;
		}
		*/

		// Тут проверяем и удаляем левые get-параметры из ссылок.
		if ( isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ) {
			$query_strings = explode( '&', $_SERVER['QUERY_STRING'] );
			foreach ( $query_strings as $key => $query_string ) {
				$query_string                     = explode( '=', $query_string );
				$query_params[ $query_string[0] ] = $query_string[1];
			}

			foreach ( $query_params as $key => $query_param ) {
				if ( in_array( $key, $allowed_seo_canonical_query_vars, true ) ) {
					$query_args[ $key ] = $query_param;
				}
			}
		}

		$seo_defaults['link']['canonical']  = trailingslashit( home_url( add_query_arg( $query_args, $wp->request ) ) );
		$seo_defaults['property']['og:url'] = trailingslashit( home_url( add_query_arg( $query_args, $wp->request ) ) );

		// Next and prev links in pagination.
		if ( is_archive() || is_home() && ! is_front_page() ) {

			if ( (int) $paged === 0 ) {
				$paged = 1;
			}

			if ( (int) $wp_query->max_num_pages > $paged ) {
				$seo_defaults['link']['next'] = get_pagenum_link( (int) $paged + 1 );
			}

			if ( is_paged() ) {
				$seo_defaults['link']['prev'] = get_pagenum_link( (int) $paged - 1 );
			}
		}

		// Title prefixes.
		if ( is_category() ) {
			$title[] = _x( 'Category:', 'category archive title prefix', 'wpgen' );
		} elseif ( is_tag() ) {
			$title[] = _x( 'Tag:', 'tag archive title prefix', 'wpgen' );
		} elseif ( is_author() ) {
			$title[] = _x( 'Author:', 'author archive title prefix', 'wpgen' );
		} elseif ( is_date() ) {
			if ( is_year() ) {
				$title[] = _x( 'Year:', 'date archive title prefix', 'wpgen' );
			} elseif ( is_month() ) {
				$title[] = _x( 'Month:', 'date archive title prefix', 'wpgen' );
			} elseif ( is_day() ) {
				$title[] = _x( 'Day:', 'date archive title prefix', 'wpgen' );
			}
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title[] = _x( 'Asides', 'post format archive title', 'wpgen' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title[] = _x( 'Galleries', 'post format archive title', 'wpgen' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title[] = _x( 'Images', 'post format archive title', 'wpgen' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title[] = _x( 'Videos', 'post format archive title', 'wpgen' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title[] = _x( 'Quotes', 'post format archive title', 'wpgen' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title[] = _x( 'Links', 'post format archive title', 'wpgen' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title[] = _x( 'Statuses', 'post format archive title', 'wpgen' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title[] = _x( 'Audio', 'post format archive title', 'wpgen' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title[] = _x( 'Chats', 'post format archive title', 'wpgen' );
			}
		} elseif ( is_post_type_archive() ) {
			$title[] = _x( 'Archives:', 'post type archive title prefix', 'wpgen' );
		}

		// Main title.
		if ( is_single() || is_page() ) {
			$title[] = get_the_title();
		} elseif ( is_archive() ) {
			$title[] = get_queried_object()->name;
		} elseif ( is_author() ) {
			$title[] = get_queried_object()->display_name ?? '';
		} elseif ( is_date() ) {
			if ( is_year() ) {
				$title[]  = get_the_date( 'Y' );
			} elseif ( is_month() ) {
				$title[]  = get_the_date( 'F Y' );
			} elseif ( is_day() ) {
				$title[]  = get_the_date( 'F j, Y' );
			}
		} elseif ( is_404() ) {
			$title[] = __( 'Page not found', 'wpgen' );
		} elseif ( is_search() ) {
			$title[] = sprintf( __( 'Search Results for &#8220;%s&#8221;', 'wpgen' ), get_search_query( false ) );
		} elseif ( is_post_type_archive() ) {
			$title[] = get_queried_object()->label;
		}

		// Добавляем информацию о сайте в заголовок
		if ( ! isset( $title ) ) {
			$title[] = get_bloginfo( 'name' );
			$title[] = $separator;
			$title[] = get_bloginfo( 'description' );
		} else {

			if ( is_paged() ) {
				$title[] = $separator;
				$title[] = __( 'Page', 'wpgen' ) . ' ' . $paged;
			}

			$title[] = $separator;
			$title[] = get_bloginfo( 'name' );
		}

		$seo_defaults['title'] = implode( ' ', $title );

		if ( is_home() || is_front_page() || is_post_type_archive() ) {
			$seo_defaults['property']['type'] = 'website';
		} else {
			$seo_defaults['property']['type'] = 'article';
		}

		$seo_defaults['property']['og:site_name'] = get_bloginfo( 'name' );
		$seo_defaults['property']['og:locale']    = determine_locale();

		if ( is_single() && has_post_thumbnail() ) {
			$attachment_image_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium' );
			$attachment_metadata  = wp_get_attachment_metadata( get_post_thumbnail_id( get_the_ID() ) );

			if ( isset( $attachment_metadata['sizes'] ) && isset( $attachment_metadata['sizes']['medium'] ) ) {
				$seo_defaults['property']['og:image']        = $attachment_image_src[0];
				$seo_defaults['property']['og:image:width']  = $attachment_metadata['sizes']['medium']['width'];
				$seo_defaults['property']['og:image:height'] = $attachment_metadata['sizes']['medium']['height'];
				$seo_defaults['property']['og:image:type']   = $attachment_metadata['sizes']['medium']['mime-type'];
			}
		}

		if ( is_single() ) {
			$seo_defaults['property']['og:updated_time']        = get_the_modified_date( 'Y-m-d\TH:i:sP' );
			$seo_defaults['property']['article:published_time'] = get_the_date( 'Y-m-d\TH:i:sP' );
			$seo_defaults['property']['article:modified_time']  = get_the_modified_date( 'Y-m-d\TH:i:sP' );
			$seo_defaults['description']                        = get_the_excerpt();
		}

		// Merge child and parent default options.
		$seo_defaults = apply_filters( 'wpgen_seo_filter_options', $seo_defaults );

		/*
		// Usage:
		add_filter( 'wpgen_seo_filter_options', 'source_wpgen_seo_filter_options' );
		function source_wpgen_seo_filter_options( $seo_defaults ) {

			$seo_defaults['meta']['type'] = 'website';

			return $seo_defaults;
		}
		*/

		// Return controls.
		if ( is_null( $control ) ) {
			return $seo_defaults;
		} elseif ( ! isset( $seo_defaults[ $control ] ) || empty( $seo_defaults[ $control ] ) ) {
			return false;
		} else {
			return $seo_defaults[ $control ];
		}
	}
}
