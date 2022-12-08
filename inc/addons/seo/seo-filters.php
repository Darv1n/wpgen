<?php
/**
 * SEO filters.
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'pre_get_document_title', 'wpgen_pre_get_document_title', 10, 3 );
if ( ! function_exists( 'wpgen_pre_get_document_title' ) ) {
	/**
	 * Function for 'pre_get_document_title' filter-hook.
	 * 
	 * @param string $title Page title.
	 *
	 * @return string
	 */
	function wpgen_pre_get_document_title( $title ) {

		if ( is_wpgen_seo_meta() && get_wpgen_seo_meta_data( 'title' ) ) {
			$title = get_wpgen_seo_meta_data( 'title' );
		}

		return $title;
	}
}

add_filter( 'document_title_separator', 'wpgen_document_title_separator', 10, 3 );
if ( ! function_exists( 'wpgen_document_title_separator' ) ) {
	/**
	 * Function for 'document_title_separator' filter-hook.
	 * 
	 * @param string $sep Title separator.
	 *
	 * @return string
	 */
	function wpgen_document_title_separator( $sep ) {

		if ( is_wpgen_seo_meta() && function_exists( get_wpgen_title_separator() ) && get_wpgen_title_separator() ) {
			$sep = get_wpgen_title_separator();
		}

		return $sep;
	}
}
