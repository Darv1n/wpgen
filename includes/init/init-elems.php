<?php
/**
 * Output elems items
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'the_elems' ) ) {

	/**
	 * Outputs the html markup of the elems.
	 *
	 * @param array $items array elems. Default: array().
	 * @param bool $echo   echo or return output html. Default: true.
	 *
	 * @return array
	 */
	function the_elems( $items = array(), $echo = true ) {

		if ( ! is_array( $items ) || empty( $items ) || count( $items ) < 3 ) {
			return false;
		}

		$html = '';

		$columns_classes = get_wpgen_archive_page_columns_classes( '', count( $items ) );

		$html .= '<div class="row">';
			foreach ( $items as $key => $item ) {
				$html .= '<div class="' . esc_attr( implode( ' ', $columns_classes ) ) . '">';

					$elem_classes[] = 'elem';
					if ( isset( $item['icon'] ) && ! empty( $item['icon'] ) ) {
						$elem_classes[] = 'elem_icon';
					}
					if ( isset( $item['bg-image'] ) && ! empty( $item['bg-image'] ) ) {
						$elem_classes[] = 'elem-bg-image';
					}

					$string = 'class="' . esc_attr( implode( ' ', $elem_classes ) ) . '"';
					if ( isset( $item['bg-image'] ) && ! empty( $item['bg-image'] ) && file_exists( trailingslashit( get_stylesheet_directory() ) . $item['bg-image'] ) ) {
						$string .= ' style="background: url( ' . esc_url( trailingslashit( get_stylesheet_directory_uri() ) . $item['bg-image'] ) . ' ) center/cover no-repeat"';
					}

					if ( isset( $item['link'] ) && ! empty( $item['link'] ) ) {
						$html .= '<a href="' . esc_url( $item['link'] ) . '" ' . $string . '>';
					} else {
						$html .= '<div ' . $string . '>';
					}
						$html .= '<div class="elem--inner">';

						if ( isset( $item['icon'] ) && ! empty( $item['icon'] ) ) {

							$file_path  = trailingslashit( get_stylesheet_directory() ) . $item['icon'];
							$image_path = trailingslashit( get_stylesheet_directory_uri() ) . $item['icon'];

							if ( file_exists( $file_path ) ) {
								$html .= '<div class="elem--icon"><img src="' . esc_url( $image_path ) . '" alt="' . esc_attr( $item['title'] ) . '"></div>';
							}
						}
						if ( isset( $item['title'] ) && ! empty( $item['title'] ) ) {
							$html .= '<h3 class="elem--title h4">' . esc_html( $item['title'] ) . '</h3>';
						}
						if ( isset( $item['description'] ) && ! empty( $item['description'] ) ) {
							$html .= '<p class="elem--description">' . esc_html( $item['description'] ) . '</p>';
						}

						$html .= '</div>';

					if ( isset( $item['link'] ) && ! empty( $item['link'] ) ) {
						$html .= '</a>';
					} else {
						$html .= '</div>';
					}
				$html .= '</div>';
			}
		$html .= '</div>';

		$html = apply_filters( 'the_elems', $html );

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
}
