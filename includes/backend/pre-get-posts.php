<?php
/**
 * WordPress action hook pre_get_posts
 *
 * @link https://developer.wordpress.org/reference/hooks/pre_get_posts/
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'pre_get_posts', 'wpgen_pre_get_posts', 1 );
if ( ! function_exists( 'wpgen_pre_get_posts' ) ) {

	/**
	 * Function for pre_get_posts action hook.
	 *
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return void
	 */
	function wpgen_pre_get_posts( $query ) {

		// Выходим, если это админ-панель или не основной запрос.
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		// Сортируем по post_type результаты поиска.
		if ( $query->is_search ) {
			$query->set( 'orderby', 'type' );
		}

	}
}
