<?php
/**
 * Template actions
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'pre_get_posts', 'wpgen_pre_get_posts', 1 );
if ( ! function_exists( 'wpgen_pre_get_posts' ) ) {

	/**
	 * Function for 'pre_get_posts' action hook.
	 *
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
	 *
	 * @return void
	 */
	function wpgen_pre_get_posts( $query ) {

		// Exit if is admin or not main query request.
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		// Sort search results by post_type.
		if ( $query->is_search ) {
			$query->set( 'orderby', 'type' );
		}
	}
}

add_action( 'wp_head', 'wpgen_page_speed_start', 1 );
if ( ! function_exists( 'wpgen_page_speed_start' ) ) {

	/**
	 * Get start page generation time in a global variable.
	 */
	function wpgen_page_speed_start() {
		$start_time             = microtime();
		$start_array            = explode( ' ', $start_time );
		$GLOBALS['start_times'] = $start_array[1] + $start_array[0]; // Пишем время в глобальную переменную.
	}
}

add_action( 'wp_footer', 'wpgen_page_speed_end', 90 );
if ( ! function_exists( 'wpgen_page_speed_end' ) ) {

	/**
	 * Print page generation time in a comment at the bottom of the source code.
	 */
	function wpgen_page_speed_end() {
		global $start_times; // получаем время из глобальной переменной.

		$end_time  = microtime();
		$end_array = explode( ' ', $end_time );
		$end_times = $end_array[1] + $end_array[0];
		$time      = $end_times - $start_times;

		sprintf( __( 'Page generated in %s seconds', 'wpgen' ), esc_html( $time ) ); // Печатаем комментарий.
	}
}

add_action( 'wp_head', 'wpgen_seo_verification', 1 );
if ( ! function_exists( 'wpgen_seo_verification' ) ) {

	/**
	 * Add verification codes on wp_head hook.
	 */
	function wpgen_seo_verification() {

		if ( wpgen_options( 'other_yandex_verification' ) ) {
			echo '<meta name="yandex-verification" content="' . esc_html( wpgen_options( 'other_yandex_verification' ) ) . '" />' . "\n";
		}
		if ( wpgen_options( 'other_google_verification' ) ) {
			echo '<meta name="google-site-verification" content="' . esc_html( wpgen_options( 'other_google_verification' ) ) . '" />' . "\n";
		}
		if ( wpgen_options( 'other_mailru_verification' ) ) {
			echo '<meta name="pmail-verification" content="' . esc_html( wpgen_options( 'other_mailru_verification' ) ) . '">' . "\n";
		}

	}
}

add_action( 'wp_footer', 'wpgen_print_counters', 25 );
if ( ! function_exists( 'wpgen_print_counters' ) ) {

	/**
	 * Print counters.
	 */
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
