<?php
/**
 * All other common functions for theme
 *
 * @package wpgen
 */

if ( !defined( 'ABSPATH' ) )
	exit;

// Печать кода
if ( !function_exists( 'vardump' ) ) {
	function vardump( $var ) {
		if ( current_user_can( 'manage_options' ) ) {
			echo '<pre>';
				var_dump( $var );
			echo '</pre>';
		}
	}
}

// првоерка, чтобы форма wpgen была активирована
if ( !function_exists( 'is_wpgen_active' ) ) {
	function is_wpgen_active() {

		if ( is_admin() || is_customize_preview() ) {
			return false;
		}
		
		if ( is_user_logged_in() ) {
			return true;
		}

		return false;

	}
}

// Получение первого и последнего ключа для старых версий
// Проверяем существование функции, чтобы не получить критическую ошибку
if ( !function_exists( 'array_key_last' ) ) {
	function array_key_last( $array ) {
		if ( !is_array( $array ) || empty( $array ) ) {
			return null;
		}
		
		return array_keys( $array )[count( $array ) - 1];
	}
}

if ( !function_exists( 'array_key_first' ) ) {
	function array_key_first( array $arr ) {
		foreach ( $arr as $key => $unused ) {
			return $key;
		}
		return null;
	}
}

// является ли переданное число четным
if ( !function_exists( 'is_int_even' ) ) {
	function is_int_even( $var ) {
		return !( (int)$var & 1 );
	}
}

// перемешать массив с сохранением ключей
if ( !function_exists( 'shuffle_assoc' ) ) {
	function shuffle_assoc( $array ) {
		$keys = array_keys( $array );

		shuffle( $keys );

		foreach( $keys as $key ) {
			$new[$key] = $array[$key];
		}

		$array = $new;

		return $array;
	}
}

// Получает контент по curl и пишем в лог файл подтвержение/ошибку
if ( !function_exists( 'get_curl_content' ) ) {
	function get_curl_content( $url, $proxy = '' ) {
		
		$ch = curl_init();
		
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36' );
		curl_setopt( $ch, CURLOPT_URL, $url );
		
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false ); // Остановка проверки сертификата
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // Возвращаем результат в строке вместо вывода в браузер
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true ); // Переходим по редиректу
		curl_setopt( $ch, CURLOPT_COOKIESESSION, true ); // Устанавливаем новую «сессию» cookies
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


// Получает slug для url из любой строки
if ( !function_exists( 'get_title_slug' ) ) {
	function get_title_slug( $string ) {
		
		$string = urldecode( sanitize_title( $string ) );
		$string = preg_replace( '/([^a-z\d\-\_])/', '', $string );
		
		return $string;
	}
}


// Получает случайную дату между двумя указанными
if ( !function_exists( 'get_random_date' ) ) {
	function get_random_date( $start_date, $end_date, $format = 'Y-m-d H:i:s' ) {
		
		$random_date = rand( strtotime( $start_date ), strtotime( $end_date ) );
		$random_date = date( $format, $random_date );
		
		return $random_date;
	}
}


// Получает последнее значение из строки, разделенной указанным сепаратором
if ( !function_exists( 'get_first_value_from_string' ) ) {
	function get_first_value_from_string( $string, $separator = ',' ) {
		
		$array = array_map( 'trim', explode( $separator, $string ) );
		
		$string = $array[0];
		
		return $string;
	}
}

// Получает последнее значение из строки, разделенной указанным сепаратором
if ( !function_exists( 'get_last_value_from_string' ) ) {
	function get_last_value_from_string( $string, $separator = ',' ) {
		
		$array = array_map( 'trim', explode( $separator, $string ) );
		$array = array_reverse( $array );
		
		$string = $array[0];
		
		return $string;
	}
}

// Получает случайную вероятность события
if ( !function_exists( 'get_probability' ) ) {
	function get_probability( $percent = 50 ) {
		
		$probability = rand( 0, 100 );
		
		if ( $percent >= $probability ) {
			return true;
		} else {
			return false;
		}
		
	}
}

// Получает первое изображение в контенте
if ( !function_exists( 'get_first_post_img' ) ) {
	function get_first_post_img() {
		global $post, $posts;
		$first_image = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );
		$first_image = $matches[1][0];
		if ( empty( $first_image ) ) {
			$first_image = get_stylesheet_directory_uri() . '/assets/img/default-banner.jpg';
		}
		return $first_image;
	}
}


// Форматирует байты в человекопонятное представление
if ( !function_exists( 'format_bytes' ) ) {
	function format_bytes( $bytes, $precision = 2 ) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		
		$bytes = max( $bytes, 0 );
		$pow = floor( ($bytes ? log( $bytes ) : 0) / log( 1024 ) );
		$pow = min( $pow, count( $units ) - 1 );
		
		// раскомментируйте одну из следующих строк
		$bytes /= pow( 1024, $pow );
		//$bytes /= (1 << (10 * $pow)); 
		
		return round( $bytes, $precision ) . ' ' . $units[$pow];
	}
}

// Считает количество слов в utf строке
if ( !function_exists( 'str_word_count_utf8' ) ) {
	function str_word_count_utf8( $string ) {
		$array = preg_split( '/\W+/u', $string, -1, PREG_SPLIT_NO_EMPTY );
		return count( $array );
	}
}


// Возвращает приблизительное время чтения статьи в строку
if ( !function_exists( 'read_time_estimate' ) ) {
	function read_time_estimate( $content ) {
		// очищаем содержимое от тегов
		// подсчитываем количество слов
		$word_count = str_word_count_utf8( strip_tags( $content ) );
		
		// 150 - средняя скорость чтения слов в минуту
		$words_per_minute = 150;
		
		// время чтения статьи в минутах
		$minutes = floor( $word_count / $words_per_minute );
		
		
		if ( $minutes == 0 ) {
			$str = '< 1';
		} elseif ( ($minutes > 0) && ($minutes <= 10) ) {
			$p = $minutes + 1;
			$str = (string)$minutes . '-' . (string)$p;
		} else {
			$p = $minutes + 5;
			$str = (string)$minutes . '-' . (string)$p;
		}
		
		return $str;
	}
}


// Возвращает строку с первой заглавной буквой
if ( !function_exists( 'mb_ucfirst' ) && extension_loaded( 'mbstring' ) ) {
	/**
	 * mb_ucfirst - преобразует первый символ в верхний регистр
	 * @param string $str - строка
	 * @param string $encoding - кодировка, по-умолчанию UTF-8
	 * @return string
	 */
	function mb_ucfirst( $str, $encoding = 'UTF-8' ) {
		$str = mb_ereg_replace( '^[\ ]+', '', $str );
		$str = mb_strtoupper( mb_substr( $str, 0, 1, $encoding ), $encoding ) . mb_substr( $str, 1, mb_strlen( $str ), $encoding );
		return $str;
	}
}

// функция конертации цвета RGB в HEX
if ( !function_exists( 'get_range_number' ) ) {
	function RGBtoHEX( $string ) {

		$rgb = explode( ', ', $string );

		if ( count( $rgb ) !== 3 )
			return $string;

		$R = dechex( preg_replace( '/\D+/', '', $rgb[0] ) );
		if ( strlen( $R ) < 2 )
			$R = '0'. $R;

		$G = dechex( preg_replace( '/\D+/', '', $rgb[1] ) );
		if ( strlen( $G ) < 2 )
			$G = '0'. $G;

		$B = dechex( preg_replace( '/\D+/', '', $rgb[2] ) );
		if ( strlen( $B ) < 2 )
			$B = '0'. $B;

		return '#' . $R . $G . $B;
	}
}

// Возвращает массив с начальным числом диапазона и конечным число диапазона в котором находится переданное число
if ( !function_exists( 'get_range_number' ) ) {
	function get_range_number( $num, $list ) {
		
		$curent_range_step = [];
		
		foreach ( $list as $key => $range ) {
			$options['min_range'] = $list[$key];
			if ( $key + 1 >= count( $list ) ) {
				$options['max_range'] = PHP_INT_MAX;
			} else {
				$options['max_range'] = $list[$key + 1];
			}
			
			$check_range = filter_var( $num, FILTER_VALIDATE_INT, array('default' => false, 'options' => $options,) );
			
			if ( $check_range !== false ) {
				$curent_range_step = [$list[$key], $list[$key + 1],];
			}
		}
		
		return $curent_range_step;
		
		/*Использование:

			$range_step = range(0, 100, 5); // получаем массив [0,5,10,15,20,25,....100]
			$result_range = get_range_number( 95, $range_step );
			vardump($result_range);

		*/
	}
}

if ( !function_exists( 'remove_emoji' ) ) {
	function remove_emoji( $string ) {
		// Match Emoticons
		$regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
		$clear_string = preg_replace( $regex_emoticons, '', $string );
		// Match Miscellaneous Symbols and Pictographs
		$regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
		$clear_string = preg_replace( $regex_symbols, '', $clear_string );
		// Match Transport And Map Symbols
		$regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
		$clear_string = preg_replace( $regex_transport, '', $clear_string );
		// Match Miscellaneous Symbols
		$regex_misc = '/[\x{2600}-\x{26FF}]/u';
		$clear_string = preg_replace( $regex_misc, '', $clear_string );
		// Match Dingbats
		$regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
		$clear_string = preg_replace( $regex_dingbats, '', $clear_string );
		
		return $clear_string;
	}
}

// Выводит webp picture
if ( !function_exists( 'get_webp_from_folders' ) ) {
	function get_webp_from_folders( $name, $folder, $folder_webp, $alt = '', $class = 'aspect-ratio', $before = '', $after = '' ) {

		$output = '';

		// получаем части изображения
		$ext = pathinfo( $name );

		// собираем пути до файлов
		$path = $folder . $ext['basename'];
		$path_webp = $folder_webp . $ext['filename'] . '.webp';

		// проверяем, что изображение существует
		if ( file_exists( get_stylesheet_directory() . $path ) ) {

			// получаем высоту и ширину изображения
			$image_attributes = getimagesize( get_stylesheet_directory() . $path );

			// проверяем, что .webp изображение существует
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
