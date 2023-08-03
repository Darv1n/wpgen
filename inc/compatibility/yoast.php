<?php
/**
 * Yoast SEO Compatibility File
 *
 * @link https://yoast.com/
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'wpseo_googlebot', '__return_false' ); // Yoast SEO 14.x or newer.
add_filter( 'wpseo_bingbot', '__return_false' ); // Yoast SEO 14.x or newer.

// Yoast filters.
if ( function_exists( 'yoast_breadcrumb' ) ) {

	if ( ! function_exists( 'wpseo_breadcrumb_output_wrapper_callback' ) ) {
		function wpseo_breadcrumb_output_wrapper_callback( $wrapper ) {
			$wrapper = 'ol';
			return $wrapper;
		}
	}
	add_filter( 'wpseo_breadcrumb_output_wrapper', 'wpseo_breadcrumb_output_wrapper_callback' );

	if ( ! function_exists( 'wpseo_breadcrumb_single_link_wrapper_callback' ) ) {
		function wpseo_breadcrumb_single_link_wrapper_callback( $wrapper ) {
			$wrapper = 'li';
			return $wrapper;
		}
	}
	add_filter( 'wpseo_breadcrumb_single_link_wrapper', 'wpseo_breadcrumb_single_link_wrapper_callback' );

	// Define the wpseo_opengraph_show_publish_date callback.
	if ( ! function_exists( 'wpseo_og_article_published_time_callback' ) ) {
		function wpseo_og_article_published_time_callback( $content ) {
			$content = get_the_modified_date();
			return $content;
		}
	}
	add_filter( 'wpseo_og_article_published_time', 'wpseo_og_article_published_time_callback', 90, 2 );

	// Добавляем закрывающий слег в теги rel="canonical".
	if ( ! function_exists( 'wpseo_canonical_callback' ) ) {
		function wpseo_canonical_callback( $canonical_url ) {
			return trailingslashit( $canonical_url );
		}
	}
	add_filter( 'wpseo_canonical', 'wpseo_canonical_callback' );

	// Add class to yoast breadcrumb link.
	if ( ! function_exists( 'wpseo_breadcrumb_single_link_callback' ) ) {
		function wpseo_breadcrumb_single_link_callback( $link ) {
			return str_replace( '<a', '<a class="' . esc_attr( implode( ' ', get_link_classes() ) ) . '"', $link ); 
		}
	}
	add_filter( 'wpseo_breadcrumb_single_link', 'wpseo_breadcrumb_single_link_callback' );

	// Yoast breadcrumb styles.
	if ( ! function_exists( 'wp_enqueue_yoast_breadcrumb_styles' ) ) {
		function wp_enqueue_yoast_breadcrumb_styles() {

			if ( wpgen_options( 'general_breadcrumbs_type' ) !== 'yoast' ) {
				return;
			}

			$css = '
				.breadcrumbs_yoast ul, .breadcrumbs_yoast ol {
					padding: 0;
					margin: 0;
					list-style-type: none;
				}

				.breadcrumbs_yoast ul * , .breadcrumbs_yoast ol * {
					vertical-align: top;
				}

				.breadcrumbs_yoast ul li, .breadcrumbs_yoast ol li {
					display: inline-block;
					margin-right: .25rem;
				}

				.breadcrumbs_yoast ul li:not(:first-child), .breadcrumbs_yoast ol li:not(:first-child) {
					display: inline-block;
					margin-left: .25rem;
				}';

			wp_add_inline_style( 'common-styles', minify_css( $css ) );
		}
	}
	add_action( 'wp_enqueue_scripts', 'wp_enqueue_yoast_breadcrumb_styles' );
}