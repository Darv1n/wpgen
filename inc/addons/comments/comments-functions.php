<?php
/**
 * Comment functions.
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter('comment_form_fields', 'wpgen_reorder_comment_fields' );
function wpgen_reorder_comment_fields( $fields ) {
	// die( vardump( $fields ) ); // Посмотрим какие поля есть.

	$new_order    = array(); // Сюда собираем поля в новом порядке.
	$order_fields = array( 'author', 'email', 'url', 'comment', 'cookies' );

	foreach ( $order_fields as $key => $order_field ) {
		if ( isset( $fields[ $order_field ] ) ) {
			$new_order[ $order_field ] = $fields[ $order_field ];
			unset( $fields[ $order_field ] );
		}
	}

	// Если остались еще какие-то поля добавим их в конец.
	if ( $fields ) {
		foreach( $fields as $key => $field ) {
			$new_fields[ $key ] = $field;
		}
	}

	return $new_order;
}
