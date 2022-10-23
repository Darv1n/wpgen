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
function external_utm_links( $content ) {

	if ( ! is_admin() && wpgen_options( 'general_external_utm_links' ) && $content !== '' ) {

		$home_host = wp_parse_url( get_home_url() )['host'];

		preg_match_all( "/\shref=\"(?<href>[^\"]+)\"/", $content, $matches );

		if ( ! empty( $matches ) ) {
			foreach ( $matches['href'] as $key => $link ) {
				$parts = wp_parse_url( $link );

				if ( isset( $parts['host'] ) && $parts['host'] !== $home_host ) {
					$new_link = add_query_arg( array( 'utm_source' => $home_host ), $link );
					// vardump( $link );
					// vardump( $new_link );
					$content  = str_replace( $link, $new_link, $content );
				}
			}
		}

		// $content = preg_replace( '/^(http[s]?):\/\//', '', $content );
	}

	return $content;
}
