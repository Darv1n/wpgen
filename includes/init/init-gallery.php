<?php
/**
 * Folder images and gallery
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'wpgen-gallery', 'the_gallery_shortcode' );
if ( ! function_exists( 'the_gallery_shortcode' ) ) {

	/**
	 * Return masonry gallery list html in shortcode.
	 * Usage: [wpgen-gallery]
	 * Usage: [wpgen-gallery folder="data/nft/" titles="data/nft.slsx" columns_count="4"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function the_gallery_shortcode( $atts ) {

		// White list of parameters and default values for the shortcode.
		$atts = shortcode_atts( array(
			'folder'        => 'data/',
			'titles'        => array(),
			'columns_count' => null,
			'inner'         => true,
			'shuffle'       => true,
			'allowed_types' => array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ),
			'caption_text'  => null,
			'caption_link'  => null,
		), $atts );

		if ( is_string( $atts['allowed_types'] ) ) {
			$atts['allowed_types'] = array_map( 'trim', explode( ',', $atts['allowed_types'] ) );
		}

		return the_gallery( $atts['folder'], $atts['titles'], $atts['columns_count'], $atts['inner'], $atts['shuffle'], $atts['allowed_types'], $atts['caption_text'], $atts['caption_link'], false );
	}
}



if ( ! function_exists( 'the_gallery' ) ) {

	/**
	 * Outputs the html markup of the gallery.
	 *
	 * @param string $folder        root folder path. Default: 'data/'.
	 * @param array $titles         array with image titles in keys. You can pass path to xlsx file, like 'data/nft.slsx'
	 * @param string $columns_count number of columns. Default: wpgen_options( 'archive_page_columns' ).
	 * @param bool $inner           include inner folders. Default: true.
	 * @param bool $shuffle         mix it up. Default: true.
	 * @param array $allowed_types  permissible file extensions. Default: array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ).
	 * @param string $caption_text  photo gallery author. Default: null.
	 * @param string $caption_link  photo gallery author link. Default: null.
	 * @param bool $echo            echo or return output html. Default: true.
	 *
	 * @return array
	 */
	function the_gallery( $folder = 'data/', $titles = array(), $columns_count = null, $inner = true, $shuffle = true, $allowed_types = array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ), $caption_text = null, $caption_link = null, $echo = true ) {

		$images = get_gallery( trailingslashit( $folder ), $inner, $shuffle, $allowed_types );

		if ( ! is_array( $images ) || empty( $images ) ) {
			return false;
		}

		// If you pass in a variable a string with the path to the xlsx file, we can take the headers from it.
		if ( is_string( $titles ) && file_exists( trailingslashit( get_stylesheet_directory() ) . $titles ) && pathinfo( $titles, PATHINFO_EXTENSION ) === 'xlsx' ) {
			$titles = get_gallery_xlsx_titles( $titles );
		}

		// If the number of columns is not passed, we take it from default option. Otherwise we run it through number to string conversion function.
		if ( is_null( $columns_count ) ) {
			$columns_count = wpgen_options( 'archive_page_columns' );
		} else {
			$columns_count = get_wpgen_count_columns( $columns_count, false );
		}

		$html = '<div class="masonry-container">';
			$html .= '<div class="row no-gutters masonry-gallery popup-gallery">';

			foreach ( $images as $key => $image ) {

				$title = '';

				if ( ! empty( $titles ) && ! empty( $titles[ pathinfo( $image, PATHINFO_FILENAME ) ] ) ) {
					$title = $titles[ pathinfo( $image, PATHINFO_FILENAME ) ];
				}

				if ( ! empty( $titles ) && ! empty( $titles[ pathinfo( $image, PATHINFO_BASENAME ) ] ) ) {
					$title = $titles[ pathinfo( $image, PATHINFO_BASENAME ) ];
				}

				if ( empty( $title ) && is_singular() ) {
					$title = get_the_title() . ' ' . $key++;
				}

				$html .= '<a href="' . esc_url( $image ) . '" class="' . esc_attr( implode( ' ', get_wpgen_archive_page_columns_classes( 'masonry-item', $columns_count ) ) ) . '" title="' . esc_attr( $title ) . '">';
					$html .= '<img src="' . esc_url( $image ) . '" class="masonry-image" alt="' . esc_attr( $title ) . '"/>';
				$html .= '</a>';

			}

			$html .= '</div>';

			if ( ! empty( $caption_text ) ) {
				$html .= '<div class="masonry-caption">';
				if ( ! empty( $caption_link ) ) {
					$html .= '<span>&#9400;&nbsp;' . __( 'Photo by', 'wpgen' ) . ':&nbsp;</span>';
					$html .= '<a class="link link-color-unborder" href="' . esc_url( $caption_link ) . '">' . esc_html( $caption_text ) . '</a>';
				} else {
					$html .= '<p>&#9400;&nbsp;' . __( 'Photo by', 'wpgen' ) . ':&nbsp;' . esc_html( $caption_text ) . '</p>';
				}
				$html .= '</div>';
			}

		$html .= '</div>';

		wp_enqueue_script( 'masonry' );

		$masonry_init = 'jQuery(function($) {
			var $container = $(".masonry-gallery");
			
			$container.imagesLoaded( function() {
				$container.masonry({
					columnWidth: ".masonry-item",
					itemSelector: ".masonry-item"
				});
			});
		});';

		wp_add_inline_script( 'masonry', $masonry_init );

		wp_enqueue_script( 'magnific-scripts' );

		$magnific_gallery_init = 'jQuery(function($) {
			$(".popup-gallery").each(function() {
				$(this).magnificPopup({
					delegate: "a",
					type: "image",
					gallery: {
						enabled:true
					},
					closeOnContentClick: true,
					mainClass: "mfp-with-zoom",
					zoom: {
						enabled: true,
						duration: 200,
						easing: "ease-in-out",
					},
					preload: [0, 2],
				})
			});
		});';

		wp_add_inline_script( 'magnific-scripts', $magnific_gallery_init );

		$html = apply_filters( 'the_gallery', $html );

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
}




if ( ! function_exists( 'get_gallery' ) ) {

	/**
	 * Return images array from root folder.
	 *
	 * @param string $folder       root folder path. Default: 'data/'.
	 * @param bool $inner          include inner folders. Default: true.
	 * @param bool $shuffle        mix it up. Default: true.
	 * @param array $allowed_types permissible file extensions. Default: array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ).
	 *
	 * @return array
	 */
	function get_gallery( $folder = 'data/', $inner = true, $shuffle = true, $allowed_types = array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ) ) {

		$directory     = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $folder );
		$images        = array();

		if ( ! is_dir( $directory ) ) {
			return false;
		}

		$images = get_folder_images( trailingslashit( $folder ), true, $allowed_types );

		foreach ( scandir( $directory ) as $key => $directory_object ) {
			if ( in_array( $directory_object, array( '.', '..' ), true ) ) {
				continue;
			}
				
			$directory_link = $directory . $directory_object;

			if ( is_dir( $directory_link ) && $inner ) {
				$gallery = get_gallery( trailingslashit( $folder ) . trailingslashit( $directory_object ), $inner, $shuffle, $allowed_types );

				if ( is_array( $gallery ) && ! empty( $gallery ) ) {
					foreach ( $gallery as $key => $gallery_value ) {
						$images[] = $gallery_value;
					}
				}
			}
		}

		if ( $shuffle ) {
			$images = shuffle_assoc( $images );
		}

		$images = apply_filters( 'get_gallery', $images );

		return $images;
	}
}



if ( ! function_exists( 'get_folder_images' ) ) {

	/**
	 * returns images from the specified folder.
	 *
	 * @param string $folder       root folder path. Default: 'data/'.
	 * @param bool $return_uri     return stylesheet_directory_ur or not. Default: true.
	 * @param array $allowed_types permissible file extensions. Default: array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ).
	 *
	 * @return array
	 */
	function get_folder_images( $folder = 'data/', $return_uri = true, $allowed_types = array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ) ) {

		$directory_uri = trailingslashit( get_stylesheet_directory_uri() ) . trailingslashit( $folder );
		$directory     = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $folder );
		$images        = array();

		if ( ! is_dir( $directory ) ) {
			return $images;
		}

		foreach ( scandir( $directory ) as $key => $directory_object ) {
			if ( in_array( $directory_object, array( '.', '..' ), true ) ) {
				continue;
			}

			$directory_link = $directory . $directory_object;
			$ext = pathinfo( $directory_link, PATHINFO_EXTENSION );

			if ( ! is_dir( $directory_link ) && in_array( $ext, (array) $allowed_types, true ) ) {
				if ( $return_uri ) {
					$images[] = $directory_uri . $directory_object;
				} else {
					$images[] = $directory . $directory_object;
				}
			}
		}

		return $images;
	}
}



if ( ! function_exists( 'get_gallery_xlsx_titles' ) ) {

	/**
	 * returns an array file name => header from the specified file.
	 *
	 * @param string $path xlsx file from theme folder
	 *
	 * @return array
	 */
	function get_gallery_xlsx_titles( $path = '' ) {

		$file_import     = trailingslashit( get_stylesheet_directory() ) . $path;
		$titles          = array();

		if ( empty( $path ) || ! file_exists( $file_import ) ) {
			return $titles;
		}

		if ( $xlsx = SimpleXLSX::parse( $file_import )) {
			$excel = $xlsx->rows(0);
			$names = array();

			foreach ( $excel as $key_d => $excel_row ) {
				if ( $key_d === 0 ) {
					foreach	( $excel_row as $key_c => $excel_col ) {
						$names[ get_title_slug( $excel_col ) ] = $key_c;
					}
				} else {
					if ( ! empty( $excel_row[ $names['name'] ] ) && !empty( $excel_row[ $names['title'] ] ) ) {
						$titles[ $excel_row[ $names['name'] ] ] = $excel_row[ $names['title'] ];
					}
				}
			}
		}

		return $titles;
	}
}
