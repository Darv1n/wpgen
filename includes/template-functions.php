<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package wpgen
 */

if ( !defined( 'ABSPATH' ) ) exit;

//	These functions print the page generation time in a comment at the bottom of the source code
//	Функция печатает время генерации страницы в комментариях внизу исходного кода
add_action( 'wp_head', 'wpgen_page_speed_start', 1 );
if ( !function_exists( 'wpgen_page_speed_start' ) ) {
	function wpgen_page_speed_start() {
		$start_time = microtime();
		$start_array = explode(" ", $start_time);
		$GLOBALS['start_times'] = $start_array[1] + $start_array[0]; // пишем время в глобальную переменную
	}
}


add_action( 'wp_footer', 'wpgen_page_speed_end', 90 );
if ( !function_exists( 'wpgen_page_speed_end' ) ) {
	function wpgen_page_speed_end() {
		global $start_times; // получаем время из глобальной переменной

		$end_time = microtime();
		
		$end_array = explode(" ", $end_time);
		$end_times = $end_array[1] + $end_array[0];

		$time = $end_times - $start_times;
		printf( "<!-- Страница сгенерирована за %f секунд -->", $time ); // печатаем комментарий
	}
}


// Заменяяем div контейнера на nav
add_filter( 'wp_nav_menu_args', 'wpgen_nav_menu_args' );
if ( !function_exists( 'wpgen_nav_menu_args' ) ) {
	function wpgen_nav_menu_args( $args = '' ) {
		if ( $args['container'] == 'div' ) {
			$args['container'] = 'nav';
		}
		return $args;
	}
}


//	The function prints noindex, nofollow tags on archive pages, if there are no posts in this archive page
//	Функция печатает теги noindex, nofollow на архивных страницах, если постов в этой архивной странице нет
add_filter( 'wp_robots', 'wpgen_robots' );
if ( !function_exists( 'wpgen_robots' ) ) {
	function wpgen_robots( $robots ) {

		if ( is_archive() && !have_posts() ) {
			$robots['noindex'] = true;
			$robots['nofollow'] = true;
		}

		$robots['max-snippet'] = '-1';
		$robots['max-image-preview'] = 'large';
		$robots['max-video-preview'] = '-1';

		return $robots;
	}
}



// Добавляем правила для файла robots.txt
add_filter( 'robots_txt', 'wpgen_robots_txt', 20, 2 );
if ( !function_exists( 'wpgen_robots_txt' ) ) {
	function wpgen_robots_txt( $output, $public ) {

		$output .= "Disallow: /wp-json\n";

		return apply_filters( 'wpgen_robots_txt', $output, $public );
	}
}



//	Add Open Graph Markup in the wp_head()
//	Добавляем разметку Open Graph в wp_head()
/*add_action( 'wp_head', 'wpgen_open_graph', 1 );
if ( !function_exists( 'wpgen_open_graph' ) ) {
	function wpgen_open_graph() {

		$output = [];

		$output['separator'] = apply_filters( 'wpgen_open_graph_separator', '|' );
		$output['bloginfo_name'] = apply_filters( 'wpgen_open_graph_bloginfo_name', get_bloginfo( 'name' ) );
		$bloginfo_description = apply_filters( 'wpgen_open_graph_bloginfo_description', get_bloginfo( 'description' ) );

		$default_description = apply_filters( 'wpgen_open_graph_default_description', '' );

		$output['title'] = '';

		if ( is_front_page() || is_home() ) {
			$output['title'] = $bloginfo_description;
		}

		if ( is_archive() ) {
			$output['title'] = get_queried_object()->name;
		}

		if ( is_post_type_archive() ) {
			$output['title'] = get_queried_object()->label;
		}

		if ( is_single() ) {
			$output['title'] = get_the_title();
		}

		vardump($output);

		if ( is_paged() ) {
			# code...
		}


		$output = implode( ' ', $output);

		echo apply_filters( 'wpgen_open_graph', $output );

	}
}

add_filter('document_title_parts', 'filter_title_part');
function filter_title_part($title) {
	vardump($title);
    return $title;
}*/


/*// Изменение заголовка в title
add_filter( 'document_title_parts', 'filter_function_name_2114' );
function filter_function_name_2114( $title ){
	if( is_page('portfolio') )
		$title['title'] = 'Моя страница портфолио — Декстер Морган';

	return $title;
}
*/

/*// пример как изменить сепаратор в разметке
add_filter( 'wpgen_open_graph_separator','my_open_graph_separator' );
function my_open_graph_separator( $separator ) {
	$separator = '—';
	return $separator;
}*/



//	Add verification codes in the wp_head()
//	Добавляем коды верификаций в wp_head()
add_action( 'wp_head', 'wpgen_seo_verification', 1 );
if ( !function_exists( 'wpgen_seo_verification' ) ) {
	function wpgen_seo_verification() {

		if ( wpgen_options( 'other_yandex_verification' ) ) {
			echo '<meta name="yandex-verification" content="' . esc_html( wpgen_options( 'other_yandex_verification' ) ) .'" />' . "\n";
		}
		if ( wpgen_options( 'other_google_verification' ) ) {
			echo '<meta name="google-site-verification" content="' . esc_html( wpgen_options( 'other_google_verification' ) ) .'" />' . "\n";
		}
		if ( wpgen_options( 'other_mailru_verification' ) ) {
			echo '<meta name="pmail-verification" content="' . esc_html( wpgen_options( 'other_mailru_verification' ) ) .'">' . "\n";
		}

	}
}


// End Footer
add_action( 'wp_footer', 'wpgen_print_counters', 25 );
if ( !function_exists( 'wpgen_print_counters' ) ) {
	function wpgen_print_counters() {

		if ( wpgen_options( 'other_yandex_counter' ) ) {
			echo '<!-- Yandex.Metrika counter -->
				<script type="text/javascript" >
					(function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
					m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
					(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

					ym(' . esc_html( wpgen_options( 'other_yandex_counter' ) ) . ', "init", {
						clickmap:true,
						trackLinks:true,
						accurateTrackBounce:true
					});
				</script>
				<!-- /Yandex.Metrika counter -->' . "\n";

		}

		if ( wpgen_options( 'other_google_counter' ) ) {
			echo '<!-- Global site tag (gtag.js) - Google Analytics -->
					<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_html( wpgen_options( 'other_google_counter' ) ) . '"></script>
					<script>
						window.dataLayer = window.dataLayer || [];
						function gtag(){dataLayer.push(arguments);}
						gtag("js", new Date());

						gtag("config", "' . esc_html( wpgen_options( 'other_google_counter' ) ) . '");
					</script>' . "\n";

		}

		if ( wpgen_options( 'other_mailru_counter' ) ) {
			echo '<!-- Rating Mail.ru counter -->
					<script type="text/javascript">
					var _tmr = window._tmr || (window._tmr = []);
					_tmr.push({id: "' . esc_html( wpgen_options( 'other_mailru_counter' ) ) . '", type: "pageView", start: (new Date()).getTime()});
					(function (d, w, id) {
						if (d.getElementById(id)) return;
						var ts = d.createElement("script"); ts.type = "text/javascript"; ts.async = true; ts.id = id;
						ts.src = "https://top-fwz1.mail.ru/js/code.js";
						var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
						if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); }
					})(document, window, "topmailru-code");
					</script>
					<!-- //Rating Mail.ru counter -->' . "\n";
		}

	}
}