<?php
/**
 * Slide images
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'the_slick_gallery' ) ) {

	/**
	 * Outputs the html markup of the gallery.
	 *
	 * @param string $folder        root folder path. Default: 'data/'.
	 * @param string $slick_param   slick init params.
	 * @param string $slick_id      slider ID. Default: random key.
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
	function the_slick_gallery( $folder = 'data/', $slick_id = null, $slick_param = null, $titles = array(), $columns_count = null, $inner = true, $shuffle = true, $allowed_types = array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ), $caption_text = null, $caption_link = null, $echo = true ) {

		$images = get_slick_gallery( trailingslashit( $folder ), $inner, $shuffle, $allowed_types );

		if ( ! is_array( $images ) || empty( $images ) ) {
			return false;
		}

		if ( is_null( $slick_id ) ) {
			$slick_id = 'slick-' . rand( 1, 999 );
		}

		// If you pass in a variable string with the path to the xlsx file, we can take the headers from it.
		if ( is_string( $titles ) && file_exists( trailingslashit( get_stylesheet_directory() ) . $titles ) && pathinfo( $titles, PATHINFO_EXTENSION ) === 'xlsx' ) {
			$titles = get_gallery_xlsx_titles( $titles );
		}

		// If the number of columns is not passed, we take it from default option. Otherwise we run it through number to string conversion function.
		if ( is_null( $columns_count ) ) {
			$columns_count = wpgen_options( 'archive_page_columns' );
		} else {
			$columns_count = get_wpgen_count_columns( $columns_count, false );
		}

		$html = '';

		$html .= '<div class="slick-container">';
			$html .= '<div id="' . esc_attr( $slick_id ) . '" class="slick popup-gallery">';

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

				$html .= '<a href="' . esc_url( $image ) . '" class="slick-item" aria-hidden="true" tabindex="-1">';
					// $html .= '<img src="' . $image . '" class="slick-image" alt="' . $title . '"/>';
					$html .= '<div class="slick-image" style="background: url( ' . esc_url( $image ) . ' ) center/contain no-repeat"></div>';
				$html .= '</a>';

			}

			$html .= '</div>';

		$html .= '</div>';

		wp_enqueue_style( 'magnific-styles' );
		wp_enqueue_script( 'magnific-scripts' );

		$magnific_gallery_init = 'jQuery(function($) {
			$(".popup-gallery").each(function() {
				$(this).magnificPopup({
					delegate: "a",
					type: "image",
					gallery: {
						enabled: true
					},
					closeOnContentClick: true,
					mainClass: "mfp-with-zoom",
					preload: [0, 2],
				})
			});
		});';

		wp_add_inline_script( 'magnific-scripts', $magnific_gallery_init );

/*		wp_enqueue_style( 'swiper-styles' );
		wp_enqueue_script( 'swiper-scripts' );

		if ( is_null( $slick_param ) ) {
			$slick_param = "const swiper = new Swiper('.swiper', {
				loop: true,
				pagination: {
					el: '.swiper-pagination',
				},
				navigation: {
					nextEl: '.swiper-button-next',
					prevEl: '.swiper-button-prev',
				},
				scrollbar: {
					el: '.swiper-scrollbar',
				},
			});";
		}

		wp_add_inline_script( 'swiper-scripts', $slick_param );
*/

		wp_enqueue_style( 'slick-styles' );
		wp_enqueue_script( 'slick-scripts' );

		if ( is_null( $slick_param ) ) {
			$slick_param = 'jQuery(function($) {

				$(".slick").each( function () {
					$( this ).slick({
						dots: true,
						infinite: true,
						speed: 300,
						slidesToShow: 4,
						slidesToScroll: 4,
						responsive: [
							{
								breakpoint: 1024,
								settings: {
									slidesToShow: 3,
									slidesToScroll: 3,
								}
							},
							{
								breakpoint: 600,
								settings: {
									slidesToShow: 2,
									slidesToScroll: 2,
								}
							},
							{
								breakpoint: 480,
								settings: {
									slidesToShow: 1,
									slidesToScroll: 1,
								}
							}
						]
					});
				});

			});';
		}

		wp_add_inline_script( 'slick-scripts', $slick_param );

		$html = apply_filters( 'the_slick_gallery', $html );

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
}


if ( ! function_exists( 'get_slick_gallery' ) ) {

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
	function get_slick_gallery( $folder = 'data/', $inner = true, $shuffle = true, $allowed_types = array( 'jpg', 'png', 'gif', 'jpeg', 'webp' ) ) {

		$directory = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $folder );
		$images    = array();

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

		$images = apply_filters( 'get_slick_gallery', $images );

		return $images;
	}
}
