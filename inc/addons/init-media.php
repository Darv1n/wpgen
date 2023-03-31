<?php
/**
 * Init Media (with google news api)
 *
 * the_media_shortcode()  - Функция для шорткода, в $args принимает все параметры функции get_media()
 * the_media()            - Принимает массив запросов, язык, кол-во элементов и пагинацию, выводит html-разметку .row>.cols>.elem.media
 * get_media()            - Принимает массив запросов и язык, возвращает массив для записи в excel таблицу
 * cmpart()               - Используется для сортировки массива по дате
 * get_media_favicon()    - Принимает url сайта, скачивает favicon, если его нет, возвращает локальную ссылку
 * add_media_query_vars() - Добавляем pg в get запросы страницы
 *
 * Shortcode usage: [media-list requests="example" lang="en" per_page="20" columns_count="five" pagination="true"]
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'media-list', 'the_media_shortcode' );
if ( ! function_exists( 'the_media_shortcode' ) ) {

	/**
	 * Return media list html in shortcode.
	 * Usage: [media-list]
	 * Usage: [media-list requests="example" lang="en" per_page="20" columns_count="five" pagination="true"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function the_media_shortcode( $atts ) {

		$lang_default = get_first_value_from_string( determine_locale(), '_' );

		// White list of parameters and default values for the shortcode.
		$atts = shortcode_atts( array(
			'requests'      => null,
			'lang'          => $lang_default,
			'per_page'      => 24,
			'columns_count' => null,
			'pagination'    => true,
			'tags'          => array( 'main' ),
			'echo'          => false,
		), $atts );

		if ( is_string( $atts['tags'] ) ) {
			$atts['tags'] = array_map( 'trim', explode( ',', $atts['tags'] ) );
		}

		return the_media( $atts['requests'], $atts['lang'], $atts['per_page'], $atts['columns_count'], $atts['pagination'], $atts['tags'], $atts['echo'] );
	}
}

if ( ! function_exists( 'the_media' ) ) {

	/**
	 * Return media list html.
	 *
	 * @param array $requests       main array with search queries. Default: none.
	 * @param string $lang          language of response from google api. Default: ru.
	 * @param int $per_page         number of items on the page. Default: 4.
	 * @param string $columns_count number of columns. Default: 4.
	 * @param bool $pagination      output the pagination or not. Default: false.
	 * @param array $tags           return marks. Default: array( 'main' ).
	 * @param bool $echo            echo or return output html. Default: true.
	 *
	 * @return echo
	 */
	function the_media( $requests = null, $lang = null, $per_page = 4, $columns_count = 4, $pagination = false, $tags = array( 'main' ), $echo = true ) {

		if ( is_null( $requests ) ) {
			$requests = apply_filters( 'get_media_requests', $requests );
		}

		if ( is_null( $requests ) ) {
			return false;
		}

		if ( is_null( $lang ) ) {
			$lang = get_first_value_from_string( determine_locale(), '_' );
		}

		$folder = apply_filters( 'get_media_file_folder', 'data/' ); // base folder
		$excel  = get_media( $requests, $lang, $tags, trailingslashit( $folder ) );

		if ( ! $excel || empty( $excel ) ) {
			return false;
		}

		if ( is_singular() ) {
			$current_link = get_permalink( get_the_ID() );
		} else {
			$current_link = get_home_url();
		}

		$html      = ''; // Main output.
		$names     = array(); // Excel first line names.
		$page_var  = get_query_var( 'pg', false );
		$home_host = wp_parse_url( get_home_url() )['host'];

		if ( ! $page_var ) {
			$keys = range( 1, $per_page );
		} else {
			$keys = range( $per_page * ( $page_var - 1) + 1, $per_page * $page_var );
		}

		$columns_count  = get_wpgen_count_columns( $columns_count, false );

		$post_classes   = array();
		$post_classes[] = 'post';
		$post_classes[] = 'post_archive';
		$post_classes[] = 'post_media';
		$post_classes[] = 'media';
		$post_classes   = apply_filters( 'get_media_post_classes', $post_classes );

		/*// Usage:
		add_filter( 'get_media_post_classes', 'my_media_post_classes' );
		if ( ! function_exists( 'my_media_post_classes' ) ) {
			function my_media_post_classes( $classes ) {
				$classes[] = 'elem';
				return array_unique( (array) $classes );
			}
		}*/

		$html .= '<div ' . wpgen_archive_page_columns_wrapper_classes( '', false ) . '>';
		foreach ( $excel as $key_d => $excel_row ) {
			if ( $key_d === 0 ) {
				foreach ( $excel_row as $key_c => $excel_col ) {
					$names[ get_title_slug( $excel_col ) ] = $key_c;
				}
			} else {
				if ( ! empty( $excel_row[ $names['title'] ] ) && ( $per_page === -1 || in_array( $key_d, $keys, true ) ) ) {
					$utm = add_query_arg( array( 'utm_source' => $home_host ), $excel_row[ $names['url'] ] );

					$parse_source_name = wp_parse_url( $excel_row[ $names['source'] ] );
					if ( ! empty( $parse_source_name['scheme'] ) ) {
						$source_name = $parse_source_name['host'];
					} else {
						$source_name = $excel_row[ $names['source'] ];
					}

					if ( isset( $names['new-title'] ) &&  ! empty( $excel_row[ $names['new-title'] ] ) ) {
						$media_title = $excel_row[ $names['new-title'] ];
					} else {
						$media_title = $excel_row[ $names['title'] ];
					}

					$html .= '<div ' . wpgen_archive_page_columns_classes( $key_d, '', $columns_count, false ) . '>';
						$html .= '<article class="' . esc_attr( implode( ' ', $post_classes ) ) . '">';
							$html .= '<div class="media-source">';
								if ( $favicon_url = get_media_favicon( $excel_row[ $names['url'] ] ) ) {
									$html .= '<img class="media-icon" src="' . esc_url( $favicon_url ) . '" alt="' . esc_attr( $source_name ) . ' icon">';
								}
								$html .= '<p>' . esc_html( $source_name ) . '</p>';
							$html .= '</div>';
							$html .= '<h3 class="media-title"><a class="media-link" href="' . esc_url( $utm ) . '" target="_blank">' . get_escape_title( $media_title ) . '</a></h3>';
							if ( determine_locale() === 'ru_RU' ) {
								$html .= '<time class="media-date" datetime="' . gmdate( 'Y-m-d\TH:i:sP', strtotime( esc_attr( $excel_row[ $names['date'] ] ) ) ) . '">' . mysql2date( 'j F Y', gmdate( 'Y-m-d', strtotime( esc_attr( $excel_row[ $names['date'] ] ) ) ) ) . '</time>';
							} else {
								$html .= '<time class="media-date" datetime="' . gmdate( 'Y-m-d\TH:i:sP', strtotime( esc_attr( $excel_row[ $names['date'] ] ) ) ) . '">' . gmdate( 'j F Y', strtotime( esc_attr( $excel_row[ $names['date'] ] ) ) ). '</time>';
							}
						$html .= '</article>';
					$html .= '</div>';
				}
			}
		}
		$html .= '</div>';

		$count = count( $excel ) - 1;

		if ( $pagination && ( int ) $count > $per_page  ) {

			$ceil = ceil( ( int ) $count / $per_page );
			$i    = 1;

			$start_page_nav = 1;
			if ( (int) get_query_var( 'pg', 1 ) > 3 ) {
				$start_page_nav = (int) get_query_var( 'pg', 1 ) - 2;
			}

			$end_page_nav = 5;
			$end_page_nav = (int) get_query_var( 'pg', 1 ) + 2;
			if ( $ceil < $end_page_nav ) {
				$end_page_nav = (int) $ceil;
			}

			$page_nav_keys = range( $start_page_nav, $end_page_nav );

			$html .= '<nav class="elem-nav">';
				$html .= '<ul class="elem-nav__list">';

				if ( (int) get_query_var( 'pg', 1 ) > 3 ) {
					$html .= '<li class="elem-nav__item elem-nav__item_first"><a class="' . esc_attr( implode( ' ', get_button_classes( 'elem-nav__link icon icon_chevron-left' ) ) ) . '" href="' . esc_url( $current_link ) . '" role="button">-1</a></li>';
				}

				while ( $i <= $ceil ) {

					if ( $i === 1 ) {
						$link = $current_link;
					} else {
						$link = add_query_arg( array( 'pg' => $i ), $current_link );
					}

					if ( in_array( $i, $page_nav_keys, true ) ) {

						$pg = (int) get_query_var( 'pg', 1 );

						if ( $pg === $i ) {
							$rel     = '';
							$classes = 'elem-nav__link elem-nav__link_current button-disabled';
						} elseif ( $pg + 1 === $i ) {
							$rel     = ' rel="next"';
							$classes = 'elem-nav__link elem-nav__link_next';
						} elseif ( $pg > 1 && $pg - 1 === $i ) {
							$rel     = ' rel="prev"';
							$classes = 'elem-nav__link elem-nav__link_prev';
						} else {
							$rel     = '';
							$classes = 'elem-nav__link';
						}

						$html .= '<li class="elem-nav__item"><a class="' . esc_attr( implode( ' ', get_button_classes( $classes ) ) ) . '" href="' . esc_url( $link ) . '" role="button"' . $rel . '>' . $i . '</a></li>';
					}

					$i++;
				}

				if ( $ceil > 3 && (int) get_query_var( 'pg', 1 ) < $ceil - 2 ) {
					$html .= '<li class="elem-nav__item elem-nav__item_last"><a class="' . esc_attr( implode( ' ', get_button_classes( 'elem-nav__link icon icon_chevron-right' ) ) ) . '" href="' . esc_url( add_query_arg( array( 'pg' => $ceil ), $current_link ) ) . '" role="button">+1</a></li>';
				}

				$html .= '</ul>';
			$html .= '</nav>';
		}

		$html = apply_filters( 'the_media', $html );

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
}

if ( ! function_exists( 'get_media' ) ) {

	/**
	 * Return media array from google news api via excel cell value true.
	 *
	 * @param array $requests main array with search queries.
	 * @param string $lang    language of response from google api.
	 * @param array $tags     return marks.
	 *
	 * @return array
	 */
	function get_media( $requests = null, $lang = null, $tags = array( 'main' ) ) {

		if ( is_null( $requests ) ) {
			return false;
		}

		if ( is_null( $lang ) ) {
			$lang = get_first_value_from_string( determine_locale(), '_' );
		}

		if ( ! isset( $folder ) ) {
			$folder = apply_filters( 'get_media_file_folder', 'data/' ); // base folder.
		}

		$file_name     = 'media-' . $lang . '.xlsx';
		$file_name     = apply_filters( 'get_media_file_name', $file_name );
		$file_import   = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $folder ) . $file_name;
		$current_date  = gmdate( 'd-m-Y' );
		$request_items = 100; // Number of items in the request.
		$titles        = array(); // Array to prevent overlapping headers.
		$excel         = array(); // Main array for return.
		$excel[]       = array( 'tag', 'date', 'title', 'url', 'source' ); // Main array titles.
		$main_tag      = ''; // First inquiry (with file creation first time).

		// Check headers in file_import file so that you don't have to parse several times.
		if ( file_exists( $file_import ) ) {

			$file_date = gmdate( 'd-m-Y', filemtime( $file_import ) );

			if ( $xlsx = SimpleXLSX::parse( $file_import ) ) {

				$excel = $xlsx->rows(0);
				$names = array();

				foreach ( $excel as $key_d => $excel_row ) {
					if ( $key_d === 0 ) {
						foreach	( $excel_row as $key_c => $excel_col ) {
							$names[ get_title_slug( $excel_col ) ] = $key_c;
						}
					} else {
						$titles[] = $excel_row[ $names['title'] ];
					}
				}
			}

			$titles = array_unique( $titles );

		} else {
			$main_tag = 'main';
		}

		// Check that current date is not the same as file date was updated.
		if ( $current_date !== $file_date ) {

			// Google api request.
			foreach ( (array) $requests as $key => $request ) {

				$loadXml = simplexml_load_file( 'http://news.google.com/news?q=' . urlencode( $request ) . '&num=' . $request_items . '&hl=' . $lang . '&output=rss' );
				vardump( 'Делаем запрос google news api' );

				if ( ! empty( $loadXml->channel->item ) ) {
					foreach ( $loadXml->channel->item as $key => $item ) {

						$title = str_replace( ' - ' . $item->source, '', $item->title );
						$title = get_first_value_from_string( $title, '|' );

						if ( ! in_array( $title, $titles, true ) ) {
							$titles[] = $title;
							$excel[]  = array( $main_tag, wp_strip_all_tags( $item->pubDate ), $title, wp_strip_all_tags( $item->link ), wp_strip_all_tags( $item->source ) );
						}
					}
				}
			} // end foreach $requests.

			// Save media posts to excel.
			if ( ! empty( $excel ) ) { 
				SimpleXLSXGen::fromArray( $excel )->saveAs( $file_import );
			}
		}

		// Retrieve specified tags from $tags.
		if ( ! empty( $tags ) && ! empty( $excel ) ) {
			$names  = array();
			$titles = array();
			foreach ( $excel as $key_d => $excel_row ) {
				if ( $key_d === 0 ) {
					foreach ( $excel_row as $key_c => $excel_col ) {
						$names[ get_title_slug( $excel_col ) ] = $key_c;
					}
				} else {
					// Remove lines with unsuitable tags, and just in case remove duplicate titles.
					if ( ! in_array( $excel_row[ $names['tag'] ], $tags, true ) || in_array( $excel_row[ $names['title'] ], $titles, true ) ) {
						unset( $excel[ $key_d ] );
					}

					$titles[] = $excel_row[ $names['title'] ];
				}
			}
		}

		// Rebuild array sorted by date.
		unset( $excel[0] ); // Delete first key.
		usort( $excel, 'cmpart' ); // Sort by date.
		$excel = array_reverse( $excel ); // Reverse array.
		array_unshift( $excel, array( 'tag', 'date', 'title', 'url', 'source' ) ); // Add first key back in.

		$excel = apply_filters( 'get_media', $excel );

		// Reset keys of array.
		$excel = array_values( $excel );

		return $excel;
	}
}

if ( ! function_exists( 'cmpart' ) ) {

	/**
	 * Return sorted values array (used to sort media by date)
	 *
	 * @param array $a first value.
	 * @param array $b second value.
	 *
	 * @return int
	 */
	function cmpart( $a, $b ) {
		if ( $a[1] === $b[1] ) {
			return 0;
		}
		return ( strtotime( $a[1] ) < strtotime( $b[1] ) ) ? -1 : 1;
	}
}

if ( ! function_exists( 'get_media_favicon' ) ) {

	/**
	 * Return site favicon link.
	 *
	 * @param string $url site url to get a favicon.
	 *
	 * @return string
	 */
	function get_media_favicon( $url = null ) {

		if ( is_null( $url ) ) {
			return false;
		}

		$folder       = apply_filters( 'get_media_favicons_folder', 'data/media-favicons/' ); // favicons folder.
		$domain_host  = wp_parse_url( $url )['host'];
		$domain_title = str_replace( 'www.', '', $domain_host );
		$domain_title = get_title_slug( $domain_title );

		$dir = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $folder );
		if ( ! is_dir( $dir ) ) {
			mkdir( $dir, 0755, true );
		}

		$file_path  = trailingslashit( get_stylesheet_directory() ) . trailingslashit( $folder ) . $domain_title . '.png';
		$image_path = trailingslashit( get_stylesheet_directory_uri() ) . trailingslashit( $folder ) . $domain_title . '.png';

		if ( ! file_exists( $file_path ) ) {
			file_put_contents ( $file_path, file_get_contents( 'https://www.google.com/s2/favicons?domain=' . $domain_host ) );
		}

		if ( file_exists( $file_path ) && filesize( $file_path ) > 0 ) {
			return $image_path;
		}

		return false;
	}
}

add_filter( 'query_vars', 'add_media_query_vars' );
if ( ! function_exists( 'add_media_query_vars' ) ) {

	/**
	 * Function for query_vars filter-hook.
	 * 
	 * @param array $public_query_vars The array of allowed query variable names.
	 *
	 * @return array
	 */
	function add_media_query_vars( $qvars ) {

		$qvars[] = 'pg';

		return array_unique( $qvars );
	}
}

add_filter( 'allowed_seo_canonical_query_vars', 'allowed_pg_canonical_query_vars' );
if ( ! function_exists( 'allowed_pg_canonical_query_vars' ) ) {

	/**
	 * Добавляем get-переменную, которая не удаляется при построении canonical ссылок
	 * 
	 * @param array $allowed_seo_canonical_query_vars The array of allowed query variable names for custom seo tags.
	 *
	 * @return array
	 */
	function allowed_pg_canonical_query_vars( $allowed_seo_canonical_query_vars ) {

		$allowed_seo_canonical_query_vars[] = 'pg';

		return array_unique( $allowed_seo_canonical_query_vars );
	}
}