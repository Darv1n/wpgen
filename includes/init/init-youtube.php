<?php
/**
 * Init Youtube videos (with youtube api)
 *
 * the_youtube_videos_shortcode()  - Функция для шорткода, в $args принимает все параметры функции get_youtube_videos()
 * the_youtube_videos()            - Принимает массив запросов, язык, кол-во элементов и пагинацию, выводит html-разметку .row>.cols>.elem.video
 * get_youtube_videos()            - Принимает массив запросов и язык, возвращает массив для записи в excel таблицу
 * youtube_search_snippet()        - Функция основного циклического запроса к youtube api, возвращает массив excel
 * get_youtube_thumbnail()         - Получает и сохраняет youtube изображение по слагу
 * add_youtube_videos_query_vars() - Добавляем pg в get запросы страницы
 *
 * Shortcode usage: [youtube-videos-list requests="channel_id: 123| q: Пример 1, Пример 2" lang="en" columns_count="3" per_page="24" pagination="true"]
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_shortcode( 'youtube-videos-list', 'the_youtube_videos_shortcode' );
if ( ! function_exists( 'the_youtube_videos_shortcode' ) ) {

	/**
	 * Return youtube video list html in shortcode.
	 * Usage: [youtube-videos-list]
	 * Usage: [youtube-videos-list requests="channel_id: 123| q: Пример 1, Пример 2" lang="en" columns_count="3" per_page="24" pagination="true"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function the_youtube_videos_shortcode( $atts ) {

		$lang_default = get_first_value_from_string( determine_locale(), '_' );

		// Белый список параметров и значения по умолчанию для шорткода.
		$atts = shortcode_atts( array(
			'requests'      => null,
			'lang'          => $lang_default,
			'per_page'      => 12,
			'columns_count' => null,
			'pagination'    => true,
			'tags'          => array( 'main' ),
			'folder'        => 'data/',
			'echo'          => false,
		), $atts );

		if ( is_string( $atts['tags'] ) ) {
			$atts['tags'] = array_map( 'trim', explode( ',', $atts['tags'] ) );
		}

		if ( ! is_null( $atts['requests'] ) ) {
			$queries  = array();
			$requests = array_map( 'trim', explode( '|', $atts['requests'] ) );
			foreach ( $requests as $key => $request ) {
				$request = array_map( 'trim', explode( ':', $request ) );
				$queries[ $request[0] ] = $request[1];
			}
		} else {
			$queries = $atts['requests'];
		}

		return the_youtube_videos( $queries, $atts['lang'], $atts['per_page'], $atts['columns_count'], $atts['pagination'], $atts['tags'], $atts['folder'], $atts['echo'] );
	}
}



if ( ! function_exists( 'the_youtube_videos' ) ) {

	/**
	 * Return video list html.
	 *
	 * @param array $requests       main array with search queries. Default: null.
	 * @param string $lang          language of response from google api. Default: ru.
	 * @param int $per_page         number of items on the page. Default: 4.
	 * @param string $columns_count number of columns. Default: wpgen_options( 'archive_page_columns' ).
	 * @param bool $pagination      output the pagination or not. Default: false.
	 * @param array $tags           return marks. Default: array( 'main' ).
	 * @param string $folder        file to save data. Default: data/.
	 * @param bool $echo            echo or return output html. Default: true.
	 *
	 * @return echo
	 */
	function the_youtube_videos( $requests = null, $lang = null, $per_page = 4, $columns_count = null, $pagination = false, $tags = array( 'main' ), $folder = 'data/', $echo = true ) {

		if ( is_null( $requests ) && wpgen_options( 'youtube_api_requests' ) ) {
			$requests = wpgen_options( 'youtube_api_requests' );
		}

		if ( is_null( $requests ) ) {
			return false;
		}

		if ( is_null( $lang ) ) {
			$lang = get_first_value_from_string( determine_locale(), '_' );
		}

		$excel = get_youtube_videos( $requests, $lang, $tags, trailingslashit( $folder ) );

		if ( ! $excel || empty( $excel ) ) {
			return false;
		}

		if ( is_singular() ) {
			$current_link = get_permalink( get_the_ID() );
		} else {
			$current_link = get_home_url();
		}

		$per_page = apply_filters( 'the_youtube_videos_per_page', $per_page );

		$html      = ''; // Main output.
		$names     = array(); // Excel first line names.
		$page_var  = get_query_var( 'pg', false );
		$home_host = wp_parse_url( get_home_url() )['host'];

		if ( ! $page_var ) {
			$keys = range( 1, $per_page );
		} else {
			$keys = range( $per_page * ( $page_var - 1) + 1, $per_page * $page_var );
		}

		if ( is_null( $columns_count ) ) {
			$columns_count = wpgen_options( 'archive_page_columns' );
		} else {
			$columns_count = get_wpgen_count_columns( $columns_count, false );
		}

		$html .= '<div class="' . implode( ' ', get_wpgen_archive_page_columns_wrapper_classes() ) . '">';
		foreach ( $excel as $key_d => $excel_row ) {
			if ( $key_d === 0 ) {
				foreach ( $excel_row as $key_c => $excel_col ) {
					$names[ get_title_slug( $excel_col ) ] = $key_c;
				}
			} else {
				if ( ! empty( $excel_row[ $names['title'] ] ) && ( $per_page === -1 || in_array( $key_d, $keys, true ) ) ) {
					$utm = add_query_arg( array( 'utm_source' => $home_host ), 'https://www.youtube.com/watch?v=' . $excel_row[ $names['slug'] ] );

					if ( ! empty( $excel_row[ $names['new-title'] ] ) ) {
						$youtube_title = $excel_row[ $names['new-title'] ];
					} else {
						$youtube_title = $excel_row[ $names['title'] ];
					}

					if ( determine_locale() !== 'ru_RU' ) {
						$youtube_title = translit( $youtube_title );
					}

					$html .= '<div class="' . implode( ' ', get_wpgen_archive_page_columns_classes( '', $columns_count ) ) . '">';
						$html .= '<article class="article-video video elem">';
							$html .= '<a class="video--thmb" href="' . esc_url( $utm ) . '" style="background: url( ' . get_youtube_thumbnail( $excel_row[ $names['slug'] ] ) . ' ) center/cover no-repeat" aria-hidden="true" tabindex="-1" target="_blank"></a>';
							$html .= '<h3 class="video--title h6"><a href="' . esc_url( $utm ) . '" class="video--link" target="_blank">' . $youtube_title . '</a></h3>';
							$html .= '<time class="video--date" datetime="' . gmdate( 'Y-m-d\TH:i:sP', strtotime( $excel_row[ $names['date'] ] ) ) . '">' . mysql2date( 'j F Y', gmdate( 'Y-m-d', strtotime( $excel_row[ $names['date'] ] ) ) ) . '</time>';
						$html .= '</article>';
					$html .= '</div>';
				}
			}
		}
		$html .= '</div>';

		$html = apply_filters( 'the_youtube_articles', $html );

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
				$html .= '<ul class="list-unstyled list-inline elem-nav--list">';

				if ( (int) get_query_var( 'pg', 1 ) > 3 ) {
					$html .= '<li class="elem-nav--item elem-nav--item-prev"><a class="' . implode( ' ', get_button_classes( 'elem-nav--link button' ) ) . '" href="' . esc_url( $current_link ) . '">-1</a></li>';
				}

				while ( $i <= $ceil ) {

					if ( $i === 1 ) {
						$link = $current_link;
					} else {
						$link = add_query_arg( array( 'pg' => $i ), $current_link );
					}

					if ( in_array( $i, $page_nav_keys, true ) ) {
						if ( (int) get_query_var( 'pg', 1 ) === $i ) {
							$html .= '<li class="elem-nav--item"><a class="' . implode( ' ', get_button_classes( 'elem-nav--link button button-default disabled' ) ) . '" href="' . esc_url( $link ) . '">' . $i . '</a></li>';
						} else {
							$html .= '<li class="elem-nav--item"><a class="' . implode( ' ', get_button_classes( 'elem-nav--link button' ) ) . '" href="' . esc_url( $link ) . '">' . $i . '</a></li>';
						}
					}

					$i++;
				}

				if ( $ceil > 5 && (int) get_query_var( 'pg', 1 ) < $ceil - 2 ) {
					$html .= '<li class="elem-nav--item elem-nav--item-next"><a class="' . implode( ' ', get_button_classes( 'elem-nav--link button' ) ) . '" href="' . esc_url( add_query_arg( array( 'pg' => $ceil ), $current_link ) ) . '">+1</a></li>';
				}

				$html .= '</ul>';
			$html .= '</nav>';
		}

		$html = apply_filters( 'the_youtube_videos', $html );

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}
}



if ( ! function_exists( 'get_youtube_videos' ) ) {

	/**
	 * Return media array from google news api via excel cell value true.
	 *
	 * @param array $requests main array with search queries.
	 * @param string $lang    language of response from google api.
	 * @param array $tags     return marks.
	 * @param string $folder  file to save data.
	 *
	 * @return array
	 */
	function get_youtube_videos( $requests = null, $lang = null, $tags = array( 'main' ), $folder = 'data/' ) {

		if ( is_null( $requests ) && wpgen_options( 'youtube_api_requests' ) ) {
			$requests = wpgen_options( 'youtube_api_requests' );
		}

		if ( is_null( $requests ) ) {
			return false;
		}

		if ( is_null( $lang ) ) {
			$lang = get_first_value_from_string( determine_locale(), '_' );
		}

		$excel         = array();
		$excel[]       = array( 'tag', 'date', 'title', 'new title', 'slug' );
		$file_import   = get_stylesheet_directory() . '/' . $folder . 'youtube-' . $lang . '.xlsx';
		$current_date  = gmdate( 'd-m-Y' );

		// Check file_import.
		if ( file_exists( $file_import ) ) {

			$file_date = gmdate( 'd-m-Y', filemtime( $file_import ) );

			if ( $xlsx = SimpleXLSX::parse( $file_import ) ) {
				$excel = $xlsx->rows(0);
			}
		}

		// Check that current date is not the same as file date was updated.
		if ( $current_date !== $file_date ) {

			foreach ( $requests as $key_r => $request ) {

				$queries = array_map( 'trim', explode( ',', $request ) );

				foreach ( $queries as $key => $query ) {
					$excel = youtube_search_snippet( $excel, $query, $key_r );
				}
			}

			// Save posts to excel.
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
					// Убираем строки с неподходящими тегами, а всякий случай убираем дубли заголовков.
					if ( ! in_array( $excel_row[ $names['tag'] ], $tags, true ) || in_array( $excel_row[ $names['title'] ], $titles, true ) ) {
						unset( $excel[ $key_d ] );
					}

					$titles[] = $excel_row[ $names['title'] ];
				}
			}
		}

		return $excel;
	}
}



if ( ! function_exists( 'youtube_search_snippet' ) ) {

	/**
	 * Return media array from google news api via excel cell value true.
	 *
	 * @param array $excel         Array for main table.
	 * @param string $request      Search query.
	 * @param string $type_request Type search query.
	 * @param string $page_token   Next page token.
	 *
	 * @return array
	 */
	function youtube_search_snippet( $excel = array(), $request = null, $type_request = 'channel_id', $page_token = null ) {

		if ( is_null( $request ) || ! wpgen_options( 'youtube_api_key' ) ) {
			return false;
		}

		$args = array(
			$type_request => $request,
			'order'       => 'date',
			'part'        => 'snippet',
			'maxResults'  => 50,
			'key'         => wpgen_options( 'youtube_api_key' ),
		);

		if ( ! is_null( $page_token ) ) {
			$args['pageToken'] = $page_token;
		}

		// Добавляем тег main (для вывода без ручной проверки) только если запрос с официального канала автора.
		if ( $type_request === 'channel_id' ) {
			$main_tag = 'main';
		} else {
			$main_tag = '';
		}

		$names       = array();
		$excel_slugs = array();

		// Список слагов нужен для проверки на уникальность значений в таблице, что не добавлять строки повторно.
		foreach ( $excel as $key_d => $excel_row ) {
			if ( $key_d === 0 ) {
				foreach	( $excel_row as $key_c => $excel_col ) {
					$names[ get_title_slug( $excel_col ) ] = $key_c;
				}
			} else {
				$excel_slugs[] = $excel_row[ $names['slug'] ];
			}
		}

		$api_url  = add_query_arg( $args, 'https://www.googleapis.com/youtube/v3/search' );
		$response = wp_remote_get( $api_url );

		vardump( 'Делаем запрос youtube api' );

		// Проверим правильный ли получили ответ.
		if ( is_wp_error( $response ) ) {
			return $response->get_error_message();
		} elseif ( wp_remote_retrieve_response_code( $response ) === 200 ) {

			// Все OK, делаем что нибудь с данными $request['body'].
			$json_result = json_decode( wp_remote_retrieve_body( $response ) );

			if ( isset( $json_result->items ) ) {
				foreach ( $json_result->items as $key => $item ) {
					if ( isset( $item->id->kind ) && $item->id->kind == 'youtube#video' && isset( $item->id->videoId ) ) {

						if ( ! in_array( $item->id->videoId, $excel_slugs, true ) ) {
							$excel[] = array( $main_tag, $item->snippet->publishTime, $item->snippet->title, '', $item->id->videoId );
						}
					}
				}
			}

			// Check if nextPageToken exist than return our function.
			if ( isset( $json_result->nextPageToken ) ) {
				$excel = youtube_search_snippet( $excel, $request, $type_request, $json_result->nextPageToken );
			}
		}

		return $excel;
	}
}



if ( ! function_exists( 'get_youtube_thumbnail' ) ) {

	/**
	 * Return site favicon link.
	 *
	 * @param string $file_name     name.
	 * @param string $folder folder which favicon saved.
	 *
	 * @return string
	 */
	function get_youtube_thumbnail( $file_name = null, $folder = 'data/youtube-thumbnail/' ) {

		if ( is_null( $file_name ) ) {
			return false;
		}

		$file_path  = get_stylesheet_directory() . '/' . $folder . get_title_slug( $file_name ) . '.jpg';
		$image_path = get_stylesheet_directory_uri() . '/' . $folder . get_title_slug( $file_name ) . '.jpg';

		if ( file_exists( $file_path ) ) {
			return $image_path;
		}

		$dir = get_stylesheet_directory() . '/' . $folder;
		if ( ! is_dir( $dir ) ) {
			mkdir( $dir, 0755, true );
		}

		$file_link = 'https://img.youtube.com/vi/' . $file_name . '/maxresdefault.jpg';

		$response = wp_remote_get( $file_link );

		if ( is_wp_error( $response ) ) {
			return $response->get_error_message();
		} elseif ( wp_remote_retrieve_response_code( $response ) === 200 ) {
			file_put_contents ( $file_path, wp_remote_retrieve_body( $response ) );
		}

		if ( ! file_exists( $file_path ) || ( file_exists( $file_path ) && filesize( $file_path ) < 2000 ) ) {
			$file_link = 'https://img.youtube.com/vi/' . $file_name . '/sddefault.jpg';

			$response = wp_remote_get( $file_link );

			if ( is_wp_error( $response ) ) {
				return $response->get_error_message();
			} elseif ( wp_remote_retrieve_response_code( $response ) === 200 ) {
				file_put_contents ( $file_path, wp_remote_retrieve_body( $response ) );
			}
		}

		if ( file_exists( $file_path ) ) {
			return $image_path;
		}

		return false;
	}
}


if ( ! function_exists( 'add_youtube_videos_query_vars' ) ) {

	/**
	 * Function for query_vars filter-hook.
	 * 
	 * @param array $public_query_vars The array of allowed query variable names.
	 *
	 * @return array
	 */
	function add_youtube_videos_query_vars( $qvars ) {

		$qvars[] = 'pg';

		return array_unique( $qvars );
	}
}
add_filter( 'query_vars', 'add_youtube_videos_query_vars' );
