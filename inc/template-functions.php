<?php
/**
 * Template functions
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'vardump' ) ) {

	/**
	 * Dump code var.
	 *
	 * @param string $var parameter for dumping.
	 */
	function vardump( $var = '' ) {
		if ( current_user_can( 'manage_options' ) ) {
			echo '<pre>';
				var_dump( $var );
			echo '</pre>';
		}
	}
}

if ( ! function_exists( 'is_wpgen_active' ) ) {

	/**
	 * Check that the wpgen form is activated.
	 *
	 * @return bool
	 */
	function is_wpgen_active() {

		if ( wpgen_options( 'general_wpgen_active' ) ) {
			$control = true;
		}

		if ( is_admin() || is_customize_preview() || ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
			$control = false;
		}

		return (bool) apply_filters( 'is_wpgen_active', $control );
	}
}

/*// Usage: change is wpgen active.
add_filter( 'is_wpgen_active', 'change_is_wpgen_active' );
if ( ! function_exists( 'change_is_wpgen_active' ) ) {
	function change_is_wpgen_active( $control ) {

		if ( preg_replace( '/^(http[s]?):\/\//', '', get_home_url() ) === 'wpgen.zolin.digital' ) {
			return true;
		}

		return $control;
	}
}*/

if ( ! function_exists( 'array_key_first' ) ) {

	/**
	 * Callback function array_key_first(), if none exists.
	 *
	 * @param array $array array to search for the first key.
	 *
	 * @return int
	 */
	function array_key_first( $array = array() ) {

		if ( ! is_array( $array ) || empty( $array ) ) {
			return null;
		}

		foreach ( $array as $key => $unused ) {
			return $key;
		}

		return null;
	}
}

if ( ! function_exists( 'array_key_last' ) ) {

	/**
	 * Callback function array_key_last(), if none exists.
	 *
	 * @param array $array array to search for the last key.
	 *
	 * @return int
	 */
	function array_key_last( $array = array() ) {

		if ( ! is_array( $array ) || empty( $array ) ) {
			return null;
		}

		return array_keys( $array )[ count( $array ) - 1 ];
	}
}

if ( ! function_exists( 'sanitize_form_field' ) ) {

	/**
	 * Form field sanitize function.
	 *
	 * @param string $string sanitize and unslash string.
	 *
	 * @return string
	 */
	function sanitize_form_field( $string = '' ) {
		return sanitize_text_field( wp_unslash( $string ) );
	}
}

if ( ! function_exists( 'is_int_even' ) ) {

	/**
	 * Whether the number transmitted is an even number.
	 *
	 * @param int $var source int.
	 *
	 * @return int
	 */
	function is_int_even( $var = 0 ) {
		return ! ( (int) $var & 1 );
	}
}

if ( ! function_exists( 'shuffle_assoc' ) ) {

	/**
	 * Shuffle the array with the keys intact.
	 *
	 * @param array $array source array.
	 *
	 * @return array
	 */
	function shuffle_assoc( $array = array() ) {

		$new  = array();
		$keys = array_keys( $array );

		shuffle( $keys );

		foreach ( $keys as $key ) {
			$new[ $key ] = $array[ $key ];
		}

		$array = $new;

		return $array;
	}
}

if ( ! function_exists( 'kses_available_tags' ) ) {

	/**
	 * Available tags for wp_kses() function.
	 *
	 * @return array
	 */
	function kses_available_tags() {

		$available_tags = array(
			'p'      => array(
				'class' => true,
			),
			'span'   => array(
				'class' => true,
			),
			'b'      => array(),
			'i'      => array(),
			'strong' => array(),
			'a'      => array(
				'href'   => true,
				'class'  => true,
				'target' => true,
			),
			'ul'     => array(),
			'ol'     => array(),
			'li'     => array(),
			'dl'     => array(),
			'dt'     => array(),
			'dd'     => array(),
			'code'   => array(),
			'pre'    => array(),
		);

		return apply_filters( 'kses_available_tags', $available_tags );
	}
}

if ( ! function_exists( 'get_curl_content' ) ) {

	/**
	 * Retrieves content via curl and writes an acknowledgement/error in the log file.
	 *
	 * @param string $url   source url for parsing.
	 * @param string $proxy proxy server.
	 *
	 * @return string
	 */
	function get_curl_content( $url, $proxy = '' ) {

		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36' );
		curl_setopt( $ch, CURLOPT_URL, $url );

		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false ); // Остановка проверки сертификата.
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Возвращаем результат в строке вместо вывода в браузер.
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true ); // Переходим по редиректу.
		curl_setopt( $ch, CURLOPT_COOKIESESSION, true ); // Устанавливаем новую «сессию» cookies.
		curl_setopt( $ch, CURLOPT_COOKIE, session_name() . '=' . session_id() );
		curl_setopt( $ch, CURLOPT_COOKIEFILE, dirname( __FILE__ ) . '/temp/cookie.txt' );
		curl_setopt( $ch, CURLOPT_COOKIEJAR, dirname( __FILE__ ) . '/temp/cookie.txt' );

		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		// curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		// curl_setopt($ch, CURLOPT_PROXY, '$proxy');

		$html = curl_exec( $ch );

		curl_close( $ch );

		return $html;
	}
}

if ( ! function_exists( 'save_remote_file' ) ) {

	/**
	 * Retrieves content via curl and writes an acknowledgement/error in the log file.
	 *
	 * @param string $file_link   External file link.
	 * @param string $file_name   File name.
	 * @param string $file_path   Path on the server to save the file.
	 * @param string $sleep       Delay after receiving a file. Default: 0.1 sec
	 *
	 * @return string
	 */
	function save_remote_file( $file_link = null, $file_name = null, $file_path = null, $sleep = 100000 ) {

		if ( is_null( $file_link ) ) {
			return;
		}

		if ( is_null( $file_name ) ) {
			$file_name = get_last_value_from_string( untrailingslashit( $file_link ), '/' );
		}

		$file_name = get_title_slug( $file_name );

		if ( is_null( $file_path ) ) {
			$html_dir   = get_stylesheet_directory() . '/data/html/';

			if ( ! is_dir( $html_dir ) ) {
				mkdir( $html_dir, 0755, true );
			}

			$file_path = trailingslashit( $html_dir ) . $file_name . '.html';
		}

		$ext = pathinfo( $file_path, PATHINFO_EXTENSION );

		// vardump( $file_link );
		// vardump( $file_name );
		// vardump( $file_path );

		// Если файла не существует, пытаемся его получить.
		if ( ! file_exists( $file_path ) ) {
			$external_html = get_curl_content( $file_link, $file_name );
			if ( $external_html !== false ) {
				$external_html     = apply_filters( 'save_remote_file', $external_html, $file_link );
				$file_put_contents = file_put_contents( $file_path, $external_html, LOCK_EX );
				if ( $file_put_contents === false ) {
					vardump( 'Возникла ошибка при парсинге файла ' . $file_name . '.' . $ext . ' (ссылка ' . $file_link . ')' );
				} else {
					vardump( 'Файл ' . $file_name . '.' . $ext . ' успешно скачан' );
				}
				usleep( (int) $sleep );
			} else {
				vardump( 'Хуевый ответ от функции get_curl_content() (ссылка ' . $file_link . ')' );
			}
		}

		return $file_path;
	}
}

if ( ! function_exists( 'get_escape_title' ) ) {

	/**
	 * Escapes and beautifies title.
	 *
	 * @param string $string source title.
	 *
	 * @return string
	 */
	function get_escape_title( $string = null ) {

		if ( is_null( $string ) ) {
			return false;
		}

		$string = wptexturize( $string );
		$string = convert_chars( $string );
		$string = esc_html( $string );
		$string = capital_P_dangit( $string );
		$string = preg_replace( '/\s+/', ' ', $string );
		$string = trim( $string );

		return $string;
	}
}

if ( ! function_exists( 'get_title_slug' ) ) {

	/**
	 * Convert title string to slug.
	 *
	 * @param string $string source title.
	 *
	 * @return string
	 */
	function get_title_slug( $string = null ) {

		if ( is_null( $string ) ) {
			return false;
		}

		if ( is_plugin_active( 'cyr2lat/cyr-to-lat.php' ) && isset( $GLOBALS['cyr_to_lat_plugin'] ) ) {
			$string = $GLOBALS['cyr_to_lat_plugin']->transliterate( $string );
		}

		$string = sanitize_title( $string );
		$string = urldecode( $string );
		$string = preg_replace( '/([^a-z\d\-\_])/', '', $string );

		return $string;
	}
}

if ( ! function_exists( 'get_random_date' ) ) {

	/**
	 * Gets a random date between two specified dates.
	 *
	 * @param string $start_date starting date.
	 * @param string $end_date   final date.
	 * @param string $format     format date for output.
	 *
	 * @return string
	 */
	function get_random_date( $start_date, $end_date, $format = 'Y-m-d H:i:s' ) {

		$random_date = wp_rand( strtotime( $start_date ), strtotime( $end_date ) );
		$random_date = gmdate( $format, $random_date );

		return $random_date;
	}
}

if ( ! function_exists( 'get_explode_part' ) ) {

	/**
	 * Gets the specified value from a string divided by the specified separator.
	 *
	 * @param string $string    source input string.
	 * @param int    $num       array key.
	 * @param string $separator separator for explode string.
	 *
	 * @return string
	 */
	function get_explode_part( $string = null, $num = 0, $separator = ',' ) {

		if ( is_null( $string ) ) {
			return false;
		}

		$num = intval( $num );

		if ( ! is_int( $num ) ) {
			return false;
		}

		$array = array_map( 'trim', explode( $separator, $string ) );

		if ( isset( $array[ $num ] ) ) {
			return $array[ $num ];
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'get_first_value_from_string' ) ) {

	/**
	 * Gets the first value from the string divided by the specified separator.
	 *
	 * @param string $string    source input string.
	 * @param string $separator separator for explode string.
	 *
	 * @return string
	 */
	function get_first_value_from_string( $string = null, $separator = ',' ) {

		if ( is_null( $string ) ) {
			return false;
		}

		$array = array_map( 'trim', explode( $separator, $string ) );

		return $array[0];
	}
}

if ( ! function_exists( 'get_last_value_from_string' ) ) {

	/**
	 * Gets the last value from the string divided by the specified separator.
	 *
	 * @param string $string    source input string.
	 * @param string $separator separator for explode string.
	 *
	 * @return string
	 */
	function get_last_value_from_string( $string = null, $separator = ',' ) {

		if ( is_null( $string ) ) {
			return false;
		}

		$array = array_map( 'trim', explode( $separator, $string ) );
		$array = array_reverse( $array );

		return $array[0];
	}
}

if ( ! function_exists( 'get_first_post_img' ) ) {

	/**
	 * Gets the first image in the content.
	 *
	 * @param object $post object for search image.
	 *
	 * @return string
	 */
	function get_first_post_img( $post = null ) {

		if ( is_null( $post ) ) {
			$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches );
		} else {
			$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
		}

		$first_image = $matches[1][0];

		if ( empty( $first_image ) ) {
			$first_image = get_stylesheet_directory_uri() . '/assets/img/default-banner.jpg';
		}

		return $first_image;
	}
}

if ( ! function_exists( 'format_bytes' ) ) {

	/**
	 * Formats bytes into a human-readable representation.
	 *
	 * @param int $bytes     number of bytes.
	 * @param int $precision number of characters for rounding.
	 *
	 * @return string
	 */
	function format_bytes( $bytes, $precision = 2 ) {

		$units = array( 'B', 'KB', 'MB', 'GB', 'TB' );

		$bytes = max( $bytes, 0 );
		$pow   = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
		$pow   = min( $pow, count( $units ) - 1 );

		// раскомментируйте одну из следующих строк.
		$bytes /= pow( 1024, $pow );
		// $bytes /= (1 << (10 * $pow));

		return round( $bytes, $precision ) . ' ' . $units[ $pow ];
	}
}

if ( ! function_exists( 'str_word_count_utf8' ) ) {

	/**
	 * Counts the number of words in the utf string.
	 *
	 * @param string $string source string.
	 *
	 * @return int
	 */
	function str_word_count_utf8( $string ) {
		$array = preg_split( '/\W+/u', $string, -1, PREG_SPLIT_NO_EMPTY );
		return count( $array );
	}
}

if ( ! function_exists( 'read_time_estimate' ) ) {

	/**
	 * Returns the approximate reading time of the article to the line.
	 *
	 * @param string $content source content.
	 *
	 * @return string
	 */
	function read_time_estimate( $content = null ) {

		if ( is_null( $content ) ) {
			return false;
		}

		// Clear the content from the tags, count the number of words.
		$word_count = str_word_count_utf8( wp_strip_all_tags( $content ) );

		// 150 - average word reading speed per minute.
		$words_per_minute = 150;

		// Article reading time in minutes.
		$minutes = floor( $word_count / $words_per_minute );

		if ( (int) $minutes === 0 ) {
			$str = '< 1';
		} elseif ( ( (int) $minutes > 0 ) && ( (int) $minutes <= 10 ) ) {
			$p   = $minutes + 1;
			$str = (string) $minutes . '-' . (string) $p;
		} else {
			$p   = $minutes + 5;
			$str = (string) $minutes . '-' . (string) $p;
		}

		return $str;
	}
}

if ( ! function_exists( 'mb_ucfirst' ) && extension_loaded( 'mbstring' ) ) {

	/**
	 * Returns the string with the first capital letter.
	 *
	 * @param string $str      source string.
	 * @param string $encoding the default encoding is UTF-8.
	 *
	 * @return string
	 */
	function mb_ucfirst( $str, $encoding = 'UTF-8' ) {
		$str = mb_ereg_replace( '^[\ ]+', '', $str );
		$str = mb_strtoupper( mb_substr( $str, 0, 1, $encoding ), $encoding ) . mb_substr( $str, 1, mb_strlen( $str ), $encoding );
		return $str;
	}
}

if ( ! function_exists( 'RGBtoHEX' ) ) {

	/**
	 * RGB to HEX color conversion function.
	 *
	 * @param string $string source string for converting.
	 *
	 * @return string
	 */
	function RGBtoHEX( $string = null ) {

		if ( is_null( $string ) ) {
			return false;
		}

		$rgb = explode( ', ', $string );

		if ( count( $rgb ) !== 3 ) {
			return $string;
		}

		$r = dechex( preg_replace( '/\D+/', '', $rgb[0] ) );
		if ( strlen( $r ) < 2 ) {
			$r = '0' . $r;
		}

		$g = dechex( preg_replace( '/\D+/', '', $rgb[1] ) );
		if ( strlen( $g ) < 2 ) {
			$g = '0' . $g;
		}

		$b = dechex( preg_replace( '/\D+/', '', $rgb[2] ) );
		if ( strlen( $b ) < 2 ) {
			$b = '0' . $b;
		}

		return '#' . $r . $g . $b;
	}
}

if ( ! function_exists( 'get_range_number' ) ) {

	/**
	 * Returns an array with the start number of the range and the end number of the range in which the passed number is located.
	 *
	 * @param int   $num  source int.
	 * @param array $list source array.
	 *
	 * @return string
	 */
	function get_range_number( $num, $list ) {

		$curent_range_step = array();

		foreach ( $list as $key => $range ) {
			$options['min_range'] = $list[ $key ];
			if ( $key + 1 >= count( $list ) ) {
				$options['max_range'] = PHP_INT_MAX;
			} else {
				$options['max_range'] = $list[ $key + 1 ];
			}

			$check_range = filter_var( $num, FILTER_VALIDATE_INT, array( 'default' => false, 'options' => $options ) );

			if ( ! $check_range ) {
				$curent_range_step = array( $list[ $key ], $list[ $key + 1 ] );
			}
		}

		return $curent_range_step;

		/*// Usage:
		$range_step = range(0, 100, 5); // получаем массив [0,5,10,15,20,25,....100]
		$result_range = get_range_number( 95, $range_step );*/
	}
}

if ( ! function_exists( 'remove_emoji' ) ) {

	/**
	 * Returns string with remove emojies.
	 *
	 * @param string $string source string.
	 *
	 * @return string
	 */
	function remove_emoji( $string = null ) {

		if ( is_null( $string ) ) {
			return false;
		}

		// Match Emoticons.
		$clear_string = preg_replace( '/[\x{1F600}-\x{1F64F}]/u', '', $string );

		// Match Miscellaneous Symbols and Pictographs.
		$clear_string = preg_replace( '/[\x{1F300}-\x{1F5FF}]/u', '', $clear_string );

		// Match Transport And Map Symbols.
		$clear_string = preg_replace( '/[\x{1F680}-\x{1F6FF}]/u', '', $clear_string );

		// Match Miscellaneous Symbols.
		$clear_string = preg_replace( '/[\x{2600}-\x{26FF}]/u', '', $clear_string );

		// Match Dingbats.
		$clear_string = preg_replace( '/[\x{2700}-\x{27BF}]/u', '', $clear_string );

		return $clear_string;
	}
}

if ( ! function_exists( 'translit' ) ) {

	/**
	 * String transliteration function.
	 *
	 * @param string $control array key to get one value.
	 *
	 * @return array
	 */
	function translit( $control = null ) {

		$converter = array(
			'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
			'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
			'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
			'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
			'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
			'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
			'э' => 'e',    'ю' => 'yu',   'я' => 'ya',

			'А' => 'A',    'Б' => 'B',    'В' => 'V',    'Г' => 'G',    'Д' => 'D',
			'Е' => 'E',    'Ё' => 'E',    'Ж' => 'Zh',   'З' => 'Z',    'И' => 'I',
			'Й' => 'Y',    'К' => 'K',    'Л' => 'L',    'М' => 'M',    'Н' => 'N',
			'О' => 'O',    'П' => 'P',    'Р' => 'R',    'С' => 'S',    'Т' => 'T',
			'У' => 'U',    'Ф' => 'F',    'Х' => 'H',    'Ц' => 'C',    'Ч' => 'Ch',
			'Ш' => 'Sh',   'Щ' => 'Sch',  'Ь' => '',     'Ы' => 'Y',    'Ъ' => '',
			'Э' => 'E',    'Ю' => 'Yu',   'Я' => 'Ya',
		);

		// Return controls.
		if ( is_null( $control ) ) {
			return $converter;
		} else {
			return strtr( $control, $converter );;
		}
	}
}
