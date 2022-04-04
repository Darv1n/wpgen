<?php
/**
 * wpgen Theme Customizer
 *
 * @package wpgen
 */

//	Default options customizer
//	Устанавливаем опции по умолчанию
function wpgen_options( $control = null ) {

	$wpgen_defaults = [
		//'general_primary_font' => 'montserrat',
		//'general_secondary_font' => 'montserrat',

		'general_color_scheme' => 'white',
		'general_header_color_scheme' => 'white',
		'general_menu_color_scheme' => 'white',
		'general_footer_color_scheme' => 'white',

		'general_container_width' => 'average',
		'general_content_width' => 'wide',
		'general_button_type' => 'common',
		'general_top_bar_display' => false,
		'general_header_type' => 'header-simple',
		'general_menu_display' => true,
		'general_menu_type' => 'menu-open',
		'general_menu_position' => 'absolute',
		'general_menu_align' => 'left',
		'general_menu_button_alignment' => 'right',
		'general_menu_button_type' => 'button-icon-text',
		'general_menu_button_icon_position' => 'right',
		'general_bottom_bar_display' => false,
		'general_footer_type' => 'footer-four-columns',

		'general_breadcrumbs_display' => true,
		'general_breadcrumbs' => 'woocommerce',
		'general_scroll_top_button_display' => true,
		'general_scroll_top_button_alignment' => 'right',
		'general_scroll_top_button_type' => 'icon-text',
		'general_scroll_top_button_icon_position' => 'left',
		'general_cookie_display' => true,

		'sidebar_display' => true,
		'sidebar_position' => 'left',
		'sidebar_display_home' => true,
		'sidebar_display_post' => true,
		'sidebar_display_page' => true,
		'sidebar_display_archive' => true,
		'sidebar_display_search' => true,
		'sidebar_display_error' => true,
		'sidebar_display_author' => false,
		//'sidebar_сontent_width' => 'narrow',

		'sidebar_left_display' => true,
		'sidebar_right_display' => false,

		'single_post_meta_display' => true,
		'single_post_template_type' => 'one',
		'single_post_meta_author_display' => false,
		'single_post_meta_date_display' => true,
		'single_post_meta_cats_display' => true,
		'single_post_meta_tags_display' => false,
		'single_post_meta_comments_display' => false,
		'single_post_meta_views_display' => true,
		'single_post_meta_time_display' => true,
		'single_post_meta_edit_display' => true,
		'single_post_date_modified_display' => false,
		'single_post_thumbnail_display' => true,
		'single_post_entry_footer_display' => true,
		'single_post_entry_footer_cats_display' => false,
		'single_post_entry_footer_tags_display' => true,
		'single_post_post_nav_display' => false,
		'single_post_similar_posts_display' => true,
		'single_post_similar_posts_orderby' => 'date',
		'single_post_similar_posts_count' => '3',

		'archive_page_columns' => 'three',
		'archive_page_template_type' => 'tils',
		'archive_page_pagination' => 'numeric',
		'archive_page_meta_display' => true,
		'archive_page_meta_author_display' => false,
		'archive_page_meta_date_display' => true,
		'archive_page_meta_cats_display' => false,
		'archive_page_meta_tags_display' => false,
		'archive_page_meta_comments_display' => false,
		'archive_page_meta_views_display' => true,
		'archive_page_meta_time_display' => true,
		'archive_page_meta_edit_display' => false,
		'archive_page_detail' => true,
		'archive_page_detail_button' => 'button',
		'archive_page_detail_description' => 'excerpt',

		'other_vkontakte' => '',
		'other_facebook' => '',
		'other_instagram' => '',
		'other_youtube' => '',
		'other_twitter' => '',
		'other_telegram' => '',
		'other_linkedin' => '',

		'other_whatsapp_phone' => '',
		'other_telegram_nick' => '',

		'other_address' => '',
		'other_phone' => '',
		'other_email' => '',

		'other_yandex_verification' => '',
		'other_google_verification' => '',
		'other_mailru_verification' => '',
		'other_yandex_counter' => '',
		'other_google_counter' => '',
		'other_mailru_counter' => '',

		'advertising_top_content' => '',
		'advertising_bottom_content' => '',
		'advertising_popup' => '',
	];

	//	Merge child and parent default options
	$wpgen_defaults = apply_filters( 'wpgen_filter_options', $wpgen_defaults );

	//	Merge defaults and theme options
	$wpgen_defaults = wp_parse_args( get_option( 'wpgen_options' ), $wpgen_defaults );

	//	Return controls
	if ( $control == null ) {
		return $wpgen_defaults;
	} else {
		return $wpgen_defaults[$control];
	}

}

// Example of overriding default options (for child themes)
// Пример переопределения опций по умолчанию (для дочерних тем)
/*add_filter( 'wpgen_filter_options','child_theme_filter_options' );
function child_theme_filter_options( $wpgen_defaults ) {

	$child_theme_defaults = array(
		'general_menu_position' => 'right',
		'sidebar_position' => 'right',
		'general_test' => false,
	);

	$wpgen_defaults = wp_parse_args( $child_theme_defaults, $wpgen_defaults);

	return $wpgen_defaults;

}*/