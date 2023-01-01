<?php
/**
 * Template filters
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'the_content', 'external_utm_links' );
if ( ! function_exists( 'external_utm_links' ) ) {

	/**
	 * Function for 'the_content' filter-hook.
	 *
	 * @param $content post content
	 *
	 * @link https://developer.wordpress.org/reference/hooks/the_content/
	 *
	 * @return string[]
	 */
	function external_utm_links( $content ) {

		if ( ! is_admin() && wpgen_options( 'general_external_utm_links' ) && $content !== '' ) {
			$pattern = '/<a (.*?)href=[\"\'](.*?)[\"\'](.*?)>(.*?)<\/a>/si';
			$content = preg_replace_callback( $pattern, 'external_utm_links_callback', $content, - 1, $count );
		}

		return $content;
	}
}

if ( ! function_exists( 'external_utm_links_callback' ) ) {
	function external_utm_links_callback( $matches ) {

		$home_parse_url  = wp_parse_url( get_home_url() );
		$match_parse_url = wp_parse_url( $matches[2] );

		if ( isset( $home_parse_url['host'] ) && isset( $match_parse_url['host'] ) && $home_parse_url['host'] === $match_parse_url['host'] ) {
			$url = $matches[2];
		} elseif ( isset( $match_parse_url['query'] ) && stripos( $match_parse_url['query'], 'utm_' ) !== false ) {
			$url = $matches[2];
		} else {
			$url = add_query_arg( array( 'utm_source' => $home_parse_url['host'] ), $matches[2] );
		}

		return '<a ' . $matches[1] . ' href="' . $url . '" ' . $matches[3] . '>' . $matches[4] . '</a>';
	}
}

add_filter( 'privacy_policy_url', 'wpgen_privacy_policy_url' );
if ( ! function_exists( 'wpgen_privacy_policy_url' ) ) {

	/**
	 * Function for 'privacy_policy_url' filter-hook.
	 * 
	 * @param string $url         The URL to the privacy policy page. Empty string if it doesn't exist.
	 * @param int $policy_page_id The ID of privacy policy page.
	 *
	 * @return string
	 */
	function wpgen_privacy_policy_url( $url ) {

		if ( empty( $url ) && is_multisite() && ! is_main_site() ) {
			switch_to_blog( 1 );
			$url = get_privacy_policy_url();
			restore_current_blog();
		}

		if ( empty( $url ) ) {
			$url = get_home_url();
		}

		return trailingslashit( $url );
	}
}

add_filter( 'wp_robots', 'wpgen_robots' );
if ( ! function_exists( 'wpgen_robots' ) ) {

	/**
	 * Function for 'wp_robots' filter-hook. Prints noindex, nofollow tags on archive pages, if there are no posts in this archive page.
	 *
	 * @param array $robots Parameter for filter.
	 *
	 * @return array
	 */
	function wpgen_robots( $robots ) {

		if ( is_archive() && ! have_posts() ) {
			$robots['noindex']  = true;
			$robots['nofollow'] = true;
		}

		$robots['max-snippet']       = '-1';
		$robots['max-image-preview'] = 'large';
		$robots['max-video-preview'] = '-1';

		return $robots;
	}
}

add_filter( 'robots_txt', 'wpgen_robots_txt', 20, 2 );
if ( ! function_exists( 'wpgen_robots_txt' ) ) {

	/**
	 * Function for 'robots_txt' filter-hook.
	 *
	 * @param string $output the robots.txt output.
	 * @param bool   $public whether the site is considered 'public'.
	 *
	 * @return string
	 */
	function wpgen_robots_txt( $output, $public ) {

		$output .= 'Disallow: /wp-json\n';

		return apply_filters( 'wpgen_robots_txt', $output, $public );
	}
}

add_filter( 'intermediate_image_sizes', 'unset_intermediate_image_sizes' );
if ( ! function_exists( 'unset_intermediate_image_sizes' ) ) {

	/**
	 * Function for 'intermediate_image_sizes' filter-hook.
	 * 
	 * @param string[] $default_sizes An array of intermediate image size names.
	 *
	 * @return string[]
	 */
	function unset_intermediate_image_sizes( $sizes ) {

		// Sizes to be removed.
		$unset_sizes = array(
			'thumbnail',
			'medium_large',
			'1536x1536',
			'2048x2048',
		);

		return array_diff( $sizes, $unset_sizes );
	}
}

add_filter( 'wp_nav_menu_args', 'wpgen_nav_menu_args' );
if ( ! function_exists( 'wpgen_nav_menu_args' ) ) {

	/**
	 * Filters the arguments used to display a navigation menu. Replace tag div with nav.
	 *
	 * @param array $args Parameter for filter.
	 *
	 * @return array
	 */
	function wpgen_nav_menu_args( $args = '' ) {
		if ( $args['container'] === 'div' ) {
			$args['container'] = 'nav';
		}
		return $args;
	}
}

add_filter( 'nav_menu_item_id', 'remove_nav_menu_item_id', 10, 3 );
if ( ! function_exists( 'remove_nav_menu_item_id' ) ) {

	/**
	 * Function for 'nav_menu_item_id' filter-hook.
	 * 
	 * @param string   $menu_id   The ID that is applied to the menu item's `<li>` element.
	 * @param WP_Post  $menu_item The current menu item.
	 * @param stdClass $args      An object of wp_nav_menu() arguments.
	 * @param int      $depth     Depth of menu item. Used for padding.
	 *
	 * @return string
	 */
	function remove_nav_menu_item_id( $id, $item, $args ) {
	    return '';
	}
}

add_filter( 'nav_menu_css_class', 'remove_nav_menu_item_class', 10, 3 );
if ( ! function_exists( 'remove_nav_menu_item_class' ) ) {

	/**
	 * Function for 'nav_menu_css_class' filter-hook.
	 * 
	 * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
	 * @param WP_Post  $menu_item The current menu item object.
	 * @param stdClass $args      An object of wp_nav_menu() arguments.
	 * @param int      $depth     Depth of menu item. Used for padding.
	 *
	 * @return string[]
	 */
	function remove_nav_menu_item_class( $classes, $item, $args ) {

		foreach ( $classes as $key => $class ) {
			if ( ! in_array( $class, array( 'menu-item', 'current-menu-item' ), true ) ) {
				unset( $classes[ $key ] ); 
			}
		}

		return $classes;
	}
}

add_filter( 'the_title', 'wpgen_search_highlight' );
add_filter( 'the_content', 'wpgen_search_highlight' );
add_filter( 'the_excerpt', 'wpgen_search_highlight' );
if ( ! function_exists( 'wpgen_search_highlight' ) ) {

	/**
	 * Highlight search results.
	 *
	 * @param string $text is text for highlight.
	 *
	 * @return string
	 */
	function wpgen_search_highlight( $text ) {

		$s = get_query_var( 's' );

		if ( is_search() && in_the_loop() && ! empty( $s ) ) :

			$style       = 'background-color:#307FE2;color:#fff;font-weight:bold;';
			$query_terms = get_query_var( 'search_terms' );

			if ( ! empty( $query_terms ) ) {
				$query_terms = explode( ' ', $s );
			}

			if ( empty( $query_terms ) ) {
				return '';
			}

			foreach ( $query_terms as $term ) {
				$term  = preg_quote( $term, '/' ); // like in search string.
				$term1 = mb_strtolower( $term ); // lowercase.
				$term2 = mb_strtoupper( $term ); // uppercase.
				$term3 = mb_convert_case( $term, MB_CASE_TITLE, 'UTF-8' ); // capitalise.
				$term4 = mb_strtolower( mb_substr( $term, 0, 1 ) ) . mb_substr( $term2, 1 ); // first lowercase.
				$text  = preg_replace( "@(?<!<|</)($term|$term1|$term2|$term3|$term4)@i", "<span style=\"{$style}\">$1</span>", $text );
			}

		endif; // is_search.

		return $text;
	}
}
