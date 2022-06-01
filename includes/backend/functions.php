<?php
/**
 * All other common functions for theme
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
	function vardump( $var ) {
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

		if ( preg_replace( '/^(http[s]?):\/\//', '', get_home_url() ) === 'wpgen.zolin.digital' ) {
			return true;
		}

		if ( is_admin() || is_customize_preview() ) {
			return false;
		}

		if ( ! is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( wpgen_options( 'general_wpgen_active' ) ) {
			return true;
		}

		return false;

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
	function array_key_last( $array ) {

		if ( ! is_array( $array ) || empty( $array ) ) {
			return null;
		}
		return array_keys( $array )[ count( $array ) - 1 ];
	}
}



if ( ! function_exists( 'array_key_first' ) ) {

	/**
	 * Callback function array_key_first(), if none exists.
	 *
	 * @param array $array array to search for the first key.
	 *
	 * @return int
	 */
	function array_key_first( $array ) {

		if ( ! is_array( $array ) || empty( $array ) ) {
			return null;
		}
		foreach ( $array as $key => $unused ) {
			return $key;
		}
		return null;
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
	function sanitize_form_field( $string ) {
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
	function is_int_even( $var ) {
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
	function shuffle_assoc( $array ) {

		$keys = array_keys( $array );
		shuffle( $keys );

		foreach ( $keys as $key ) {
			$new[ $key ] = $array[ $key ];
		}

		$array = $new;

		return $array;
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



if ( ! function_exists( 'get_title_slug' ) ) {

	/**
	 * Convert title string to slug.
	 *
	 * @param string $string source title.
	 *
	 * @return string
	 */
	function get_title_slug( $string ) {

		$string = urldecode( sanitize_title( $string ) );
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
	function get_explode_part( $string, $num = 0, $separator = ',' ) {

		$num = intval( $num );

		if ( ! is_int( $num ) ) {
			return false;
		}

		$array = array_map( 'trim', explode( $separator, $string ) );

		return $array[ $num ];
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
	function get_first_value_from_string( $string, $separator = ',' ) {

		$array = array_map( 'trim', explode( $separator, $string ) );

		$string = $array[0];

		return $string;
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
	function get_last_value_from_string( $string, $separator = ',' ) {
		$array = array_map( 'trim', explode( $separator, $string ) );
		$array = array_reverse( $array );

		$string = $array[0];

		return $string;
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
	function read_time_estimate( $content ) {

		// Clear the content from the tags, count the number of words.
		$word_count = str_word_count_utf8( wp_strip_all_tags( $content ) );

		// 150 - average word reading speed per minute.
		$words_per_minute = 150;

		// Article reading time in minutes.
		$minutes = floor( $word_count / $words_per_minute );

		if ( $minutes === 0 ) {
			$str = '< 1';
		} elseif ( ( $minutes > 0 ) && ( $minutes <= 10 ) ) {
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
	function RGBtoHEX( $string ) {

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

		/*
		Usage:
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
	function remove_emoji( $string ) {

		// Match Emoticons.
		$regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
		$clear_string = preg_replace( $regex_emoticons, '', $string );

		// Match Miscellaneous Symbols and Pictographs.
		$regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
		$clear_string = preg_replace( $regex_symbols, '', $clear_string );

		// Match Transport And Map Symbols.
		$regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
		$clear_string = preg_replace( $regex_transport, '', $clear_string );

		// Match Miscellaneous Symbols.
		$regex_misc = '/[\x{2600}-\x{26FF}]/u';
		$clear_string = preg_replace( $regex_misc, '', $clear_string );

		// Match Dingbats.
		$regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
		$clear_string = preg_replace( $regex_dingbats, '', $clear_string );

		return $clear_string;
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
	function get_webp_from_folders( $name, $folder, $folder_webp, $alt = '', $class = 'aspect-ratio', $before = '', $after = '' ) {

		$output = '';

		// получаем части изображения.
		$ext = pathinfo( $name );

		// собираем пути до файлов.
		$path      = $folder . $ext['basename'];
		$path_webp = $folder_webp . $ext['filename'] . '.webp';

		// проверяем, что изображение существует.
		if ( file_exists( get_stylesheet_directory() . $path ) ) {

			// получаем высоту и ширину изображения.
			$image_attributes = getimagesize( get_stylesheet_directory() . $path );

			// проверяем, что .webp изображение существует.
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

		return $before . $output . $after;
	}
}
