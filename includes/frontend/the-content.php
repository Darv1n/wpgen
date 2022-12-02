<?php
/**
 * WordPress action hook the_content
 *
 * @link https://developer.wordpress.org/reference/hooks/the_content/
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'the_content', 'external_utm_links' );
if ( ! function_exists( 'external_utm_links' ) ) {
	function external_utm_links( $content ) {

		if ( ! is_admin() && wpgen_options( 'general_external_utm_links' ) && $content !== '' ) {
			$pattern = '/<a (.*?)href=[\"\'](.*?)[\"\'](.*?)>(.*?)<\/a>/si';
			$content = preg_replace_callback( $pattern, 'external_utm_links_parser', $content, - 1, $count );
		}

		return $content;
	}
}

if ( ! function_exists( 'external_utm_links_parser' ) ) {
	function external_utm_links_parser( $matches ) {

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
