<?php
/**
 * Deprecated functions.
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
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