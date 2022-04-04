<?php
/**
 * pre_get_posts WordPress function
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// pre_get_posts
add_action( 'pre_get_posts', 'wpgen_pre_get_posts', 1 );
if ( !function_exists( 'wpgen_pre_get_posts' ) ) {
	function wpgen_pre_get_posts( $query ) {

		// Выходим, если это админ-панель или не основной запрос
		if ( is_admin() || ! $query->is_main_query() )
			return;

		// сортируем по post_type результаты поиска
		if ( $query->is_search ) {
			$query->set( 'orderby', 'type' );
		}
		
	}
}
