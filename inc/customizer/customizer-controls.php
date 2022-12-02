<?php
/**
 * Wpgen customizer controls array
 *
 * @package wpgen
 */

if ( ! function_exists( 'get_wpgen_customizer_controls' ) ) {

	/**
	 * Return array with customizer controls.
	 *
	 * @param string $control array key to get one value.
	 *
	 * @return array
	 */
	function get_wpgen_customizer_controls( $control = null ) {

		// Selects.
		$general_color_scheme_select = array(
			'white' => __( 'White', 'wpgen' ),
			'light' => __( 'Light', 'wpgen' ),
			'dark'  => __( 'Dark', 'wpgen' ),
			'black' => __( 'Black', 'wpgen' ),
		);

		$general_container_width_select = array(
			'narrow'  => __( 'Narrow', 'wpgen' ),
			'general' => __( 'General', 'wpgen' ),
			'average' => __( 'Average', 'wpgen' ),
			'wide'    => __( 'Wide', 'wpgen' ),
			'fluid'   => __( 'Fluid', 'wpgen' ),
		);

		$general_content_width_select = array(
			'narrow' => __( 'Narrow', 'wpgen' ),
			'wide'   => __( 'Wide', 'wpgen' ),
		);

		$general_button_type_select = array(
			'common'   => __( 'Common', 'wpgen' ),
			'gradient' => __( 'Gradient', 'wpgen' ),
			'slide'    => __( 'Slide', 'wpgen' ),
		);

		$general_header_type_select = array(
			'header-simple'      => __( 'Header Simple', 'wpgen' ),
			'header-logo-center' => __( 'Header Logo Center', 'wpgen' ),
			'header-content'     => __( 'Header Content', 'wpgen' ),
		);

		$general_menu_type_select = array(
			'menu-open'  => __( 'Open', 'wpgen' ),
			'menu-close' => __( 'Close', 'wpgen' ),
		);

		$general_menu_position_select = array(
			'relative' => __( 'Relative', 'wpgen' ),
			'absolute' => __( 'Absolute', 'wpgen' ),
			'fixed'    => __( 'Fixed', 'wpgen' ),
		);

		$general_menu_align_select = array(
			'right'  => __( 'Right', 'wpgen' ),
			'left'   => __( 'Left', 'wpgen' ),
			'center' => __( 'Center', 'wpgen' ),
		);

		$general_menu_button_align_select = array(
			'right'  => __( 'Right', 'wpgen' ),
			'left'   => __( 'Left', 'wpgen' ),
			'center' => __( 'Center', 'wpgen' ),
		);

		$general_menu_button_type_select = array(
			'button-icon-text' => __( 'Button + Icon + Text', 'wpgen' ),
			'button-icon'      => __( 'Button + Icon', 'wpgen' ),
			'button-text'      => __( 'Button + Text', 'wpgen' ),
			'icon'             => __( 'Icon', 'wpgen' ),
			'icon-text'        => __( 'Icon + Text', 'wpgen' ),
			'text'             => __( 'Text', 'wpgen' ),
		);

		$general_breadcrumbs_select = array(
			'woocommerce' => __( 'WooCommerce (Plugin must be activated)', 'wpgen' ),
			'yoast'       => __( 'Yoast (Must be enabled in the plugin)', 'wpgen' ),
			'rankmath'    => __( 'RankMath (Must be enabled in the plugin)', 'wpgen' ),
			'seopress'    => __( 'SEOPress (Must be enabled in the plugin)', 'wpgen' ),
		);

		$general_footer_type_select = array(
			'footer-simple'        => __( 'Footer Simple', 'wpgen' ),
			'footer-three-columns' => __( 'Footer Three Columns', 'wpgen' ),
			'footer-four-columns'  => __( 'Footer Four Columns', 'wpgen' ),
		);

		$alignment_select = array(
			'right' => __( 'Right', 'wpgen' ),
			'left'  => __( 'Left', 'wpgen' ),
		);

		$single_post_template_type_select = array(
			'one' => __( 'One', 'wpgen' ),
			'two' => __( 'Two', 'wpgen' ),
		);

		$single_post_similar_posts_orderby_select = array(
			'date' => __( 'Date', 'wpgen' ),
			'rand' => __( 'Random', 'wpgen' ),
		);

		$archive_page_columns_select = array(
			'one'   => __( 'One', 'wpgen' ),
			'two'   => __( 'Two', 'wpgen' ),
			'three' => __( 'Three', 'wpgen' ),
			'four'  => __( 'Four', 'wpgen' ),
			'five'  => __( 'Five', 'wpgen' ),
			'six'   => __( 'Six', 'wpgen' ),
		);

		$archive_page_template_type_select = array(
			'list'    => __( 'List', 'wpgen' ),
			'tils'    => __( 'Tils', 'wpgen' ),
			'banners' => __( 'Banners', 'wpgen' ),
			'simple'  => __( 'Simple', 'wpgen' ),
		);

		$archive_page_pagination_select = array(
			'default' => __( 'Default', 'wpgen' ),
			'numeric' => __( 'Numeric', 'wpgen' ),
		);

		$archive_page_detail_description_select = array(
			'nothing' => __( 'Nothing', 'wpgen' ),
			'excerpt' => __( 'Excerpt', 'wpgen' ),
			'content' => __( 'Content', 'wpgen' ),
		);

		$archive_page_detail_button_select = array(
			'button'  => __( 'Button', 'wpgen' ),
			'link'    => __( 'Link', 'wpgen' ),
			'nothing' => __( 'Nothing', 'wpgen' ),
		);

		$bg_image_size = array(
			'cover'   => __( 'Cover', 'wpgen' ),
			'initial' => __( 'Pattern', 'wpgen' ),
		);

		// Add description to translate.
		$general_container_width_description       = __( 'Here you can change the width of the site', 'wpgen' );
		$general_content_width_description         = __( 'Note: its work if sidebar dont show', 'wpgen' );
		$general_menu_position_description         = __( 'Position of the menu container when opened', 'wpgen' );
		$general_menu_align_description            = __( 'Alignment of the menu container', 'wpgen' );
		$general_wpgen_active                      = __( 'This checkbox activates the WpGen form in the frontend for the site administrator', 'wpgen' );
		$general_top_bar_description               = __( 'This checkbox displays two sidebars at the header of the site. They are adds in the widget section options', 'wpgen' );
		$general_bottom_bar_description            = __( 'This checkbox displays two sidebars at the footer of the site. They are adds in the widget section options', 'wpgen' );
		$general_comments_display_description      = __( 'Comments block hide/display', 'wpgen' );
		$general_cookie_display_description        = __( 'Displays a notification about the use of cookies on the site', 'wpgen' );
		$general_external_utm_links_description    = __( 'Adds utm tags to all external links', 'wpgen' );
		$sidebar_display_description               = __( 'Display sidebar?', 'wpgen' );
		$sidebar_position_description              = __( 'This field displays the sidebar to the left or right of the main content; on mobile devices, the sidebar is displayed after the main content', 'wpgen' );
		$single_post_template_type_description     = __( 'This field displays template of the post', 'wpgen' );
		$archive_page_columns_description          = __( 'Choose how many columns to display posts', 'wpgen' );
		$archive_page_template_type_description    = __( 'This field displays template of posts', 'wpgen' );
		$other_tab_social_list_description         = __( 'Add a link to social-list networks using a shortcode <strong>[wpgen-social-list]</strong>', 'wpgen' );
		$other_tab_contacts_list_description       = __( 'Add a link to contacts-list using a shortcode <strong>[wpgen-contacts-list]</strong> or single <strong>[wpgen-email]</strong>, <strong>[wpgen-phone]</strong>, <strong>[wpgen-address]</strong>', 'wpgen' );
		$other_tab_whatsapp_phone_description      = __( 'Enter data in the format 7999XXXXXXX without "+"', 'wpgen' );
		$other_tab_viber_phone_description         = __( 'Enter data in the format 7999XXXXXXX without "+"', 'wpgen' );
		$other_tab_telegram_nick_description       = __( 'Enter you nick in telegram', 'wpgen' );
		$other_tab_yandex_verification_description = __( 'Get your yandex verification code in the Yandex Webmaster Tools', 'wpgen' );
		$other_tab_google_verification_description = __( 'Get your google verification code in the Google Search Console', 'wpgen' );
		$other_tab_mailru_verification_description = __( 'Get your mail ru verification code in the Mail ru Pulse', 'wpgen' );
		$other_tab_yandex_counter_description      = __( 'Get yandex counter ID like a ********', 'wpgen' );
		$other_tab_google_counter_description      = __( 'Get google counter ID like a UA-********-*', 'wpgen' );
		$other_tab_mailru_counter_description      = __( 'Get mailru counter ID like a *******', 'wpgen' );


		$wpgen_controls = array();

		// General common options.
		$wpgen_controls['general'] = array(
			'content_title'               => array( 'tab_title', __( 'Content', 'wpgen' ), '' ),
			'wpgen_active'                => array( 'checkbox_control', __( 'Top bar display', 'wpgen' ), $general_wpgen_active ),
			'color_scheme'                => array( 'select_control', __( 'Content color scheme', 'wpgen' ), '', $general_color_scheme_select ),
			'container_width'             => array( 'select_control', __( 'Select container width', 'wpgen' ), $general_container_width_description, $general_container_width_select ),
			'content_width'               => array( 'select_control', __( 'Select content width', 'wpgen' ), $general_content_width_description, $general_content_width_select ),
			'button_type'                 => array( 'select_control', __( 'Select button type', 'wpgen' ), '', $general_button_type_select ),

			'header_title'                => array( 'tab_title', __( 'Header', 'wpgen' ), '' ),
			'top_bar_display'             => array( 'checkbox_control', __( 'Top bar display', 'wpgen' ), $general_top_bar_description ),
			'header_color_scheme'         => array( 'select_control', __( 'Header color scheme', 'wpgen' ), '', $general_color_scheme_select ),
			'header_type'                 => array( 'select_control', __( 'Select header type', 'wpgen' ), '', $general_header_type_select ),

			'menu_display'                => array( 'checkbox_control', __( 'Menu display', 'wpgen' ), '' ),
			'menu_type'                   => array( 'select_control', __( 'Select menu type', 'wpgen' ), '', $general_menu_type_select ),
			'menu_color_scheme'           => array( 'select_control', __( 'Menu color scheme', 'wpgen' ), '', $general_color_scheme_select ),
			'menu_position'               => array( 'select_control', __( 'Select menu position', 'wpgen' ), $general_menu_position_description, $general_menu_position_select ),
			'menu_align'                  => array( 'select_control', __( 'Select menu alignment', 'wpgen' ), $general_menu_align_description, $general_menu_align_select ),
			'menu_button_alignment'       => array( 'select_control', __( 'Select menu button alignment', 'wpgen' ), '', $general_menu_button_align_select ),
			'menu_button_type'            => array( 'select_control', __( 'Select menu button type', 'wpgen' ), '', $general_menu_button_type_select ),

			'footer_title'                => array( 'tab_title', __( 'Footer', 'wpgen' ), '' ),
			'bottom_bar_display'          => array( 'checkbox_control', __( 'Bottom bar display', 'wpgen' ), $general_bottom_bar_description ),
			'footer_color_scheme'         => array( 'select_control', __( 'Footer color scheme', 'wpgen' ), '', $general_color_scheme_select ),
			'footer_type'                 => array( 'select_control', __( 'Select footer type', 'wpgen' ), '', $general_footer_type_select ),

			'breadcrumbs_display'         => array( 'checkbox_control', __( 'Breadcrumbs display', 'wpgen' ), '' ),
			'breadcrumbs'                 => array( 'select_control', __( 'Select position', 'wpgen' ), '', $general_breadcrumbs_select ),

			'scroll_top_button_display'   => array( 'checkbox_control', __( 'Scroll to top button display', 'wpgen' ), '' ),
			'scroll_top_button_alignment' => array( 'select_control', __( 'Select scroll top button alignment', 'wpgen' ), '', $alignment_select ),
			'scroll_top_button_type'      => array( 'select_control', __( 'Select scroll top button type', 'wpgen' ), '', $general_menu_button_type_select ),

			'cookie_title'                => array( 'tab_title', __( 'Other', 'wpgen' ), '' ),
			'comments_display'            => array( 'checkbox_control', __( 'Comments display', 'wpgen' ), $general_comments_display_description ),
			'cookie_display'              => array( 'checkbox_control', __( 'Cookie display', 'wpgen' ), $general_cookie_display_description ),
			'external_utm_links'          => array( 'checkbox_control', __( 'External UTM Links', 'wpgen' ), $general_external_utm_links_description ),
		);

		// Sidebar options.
		$wpgen_controls['sidebar'] = array(
			'tab_title'       => array( 'tab_title', __( 'Which pages display sidebar', 'wpgen' ), '' ),
			'left_display'    => array( 'checkbox_control', __( 'Left Sidebar Display', 'wpgen' ), '' ),
			'right_display'   => array( 'checkbox_control', __( 'Right Sidebar Display', 'wpgen' ), '' ),
			'display_home'    => array( 'checkbox_control', __( 'Home page', 'wpgen' ), '' ),
			'display_post'    => array( 'checkbox_control', __( 'Single post', 'wpgen' ), '' ),
			'display_page'    => array( 'checkbox_control', __( 'Single page', 'wpgen' ), '' ),
			'display_archive' => array( 'checkbox_control', __( 'Archive page', 'wpgen' ), '' ),
			'display_search'  => array( 'checkbox_control', __( 'Search page', 'wpgen' ), '' ),
			'display_error'   => array( 'checkbox_control', __( '404 page', 'wpgen' ), '' ),
			'display_author'  => array( 'checkbox_control', __( 'Author page', 'wpgen' ), '' ),
		);

		// Single Post options.
		$wpgen_controls['single_post'] = array(
			'meta_display'              => array( 'checkbox_control', __( 'Meta display', 'wpgen' ), '' ),
			'template_type'             => array( 'select_control', __( 'Select Template Type', 'wpgen' ), $single_post_template_type_description, $single_post_template_type_select ),
			'meta_author_display'       => array( 'checkbox_control', __( 'Meta author display', 'wpgen' ), '' ),
			'meta_date_display'         => array( 'checkbox_control', __( 'Meta publish date display', 'wpgen' ), '' ),
			'meta_cats_display'         => array( 'checkbox_control', __( 'Meta categoties display', 'wpgen' ), '' ),
			'meta_tags_display'         => array( 'checkbox_control', __( 'Meta tags display', 'wpgen' ), '' ),
			'meta_comments_display'     => array( 'checkbox_control', __( 'Meta comments display', 'wpgen' ), '' ),
			'meta_time_display'         => array( 'checkbox_control', __( 'Meta read time display', 'wpgen' ), '' ),
			'meta_views_display'        => array( 'checkbox_control', __( 'Meta views display', 'wpgen' ), '' ),
			'meta_edit_display'         => array( 'checkbox_control', __( 'Meta edit display', 'wpgen' ), '' ),

			'tab_title'                 => array( 'tab_title', __( 'Post options', 'wpgen' ), '' ),
			'date_modified_display'     => array( 'checkbox_control', __( 'Date modified display', 'wpgen' ), '' ),
			'thumbnail_display'         => array( 'checkbox_control', __( 'Thumbnail display', 'wpgen' ), '' ),
			'similar_posts_display'     => array( 'checkbox_control', __( 'Similar posts display', 'wpgen' ), '' ),
			'similar_posts_orderby'     => array( 'select_control', __( 'Select posts orderby', 'wpgen' ), '', $single_post_similar_posts_orderby_select ),
			'similar_posts_count'       => array( 'number_control', __( 'Select posts count', 'wpgen' ), array( 'step' => '1' ) ),

			'entry_footer_display'      => array( 'checkbox_control', __( 'Entry Footer display', 'wpgen' ), '' ),
			'entry_footer_cats_display' => array( 'checkbox_control', __( 'Entry Footer cats display', 'wpgen' ), '' ),
			'entry_footer_tags_display' => array( 'checkbox_control', __( 'Entry Footer tags display', 'wpgen' ), '' ),
			'post_nav_display'          => array( 'checkbox_control', __( 'Prev/next post navigation display', 'wpgen' ), '' ),
		);

		// Archive Page options.
		$wpgen_controls['archive_page'] = array(
			'columns'               => array( 'select_control', __( 'Select columns of posts', 'wpgen' ), $archive_page_columns_description, $archive_page_columns_select ),
			'template_type'         => array( 'select_control', __( 'Select template type', 'wpgen' ), $archive_page_template_type_description, $archive_page_template_type_select ),
			'pagination'            => array( 'select_control', __( 'Post Pagination', 'wpgen' ), '', $archive_page_pagination_select ),

			'meta_display'          => array( 'checkbox_control', __( 'Meta display', 'wpgen' ), '' ),
			'meta_author_display'   => array( 'checkbox_control', __( 'Meta author display', 'wpgen' ), '' ),
			'meta_date_display'     => array( 'checkbox_control', __( 'Meta publish date display', 'wpgen' ), '' ),
			'meta_cats_display'     => array( 'checkbox_control', __( 'Meta categoties display', 'wpgen' ), '' ),
			'meta_tags_display'     => array( 'checkbox_control', __( 'Meta tags display', 'wpgen' ), '' ),
			'meta_comments_display' => array( 'checkbox_control', __( 'Meta comments display', 'wpgen' ), '' ),
			'meta_time_display'     => array( 'checkbox_control', __( 'Meta read time display', 'wpgen' ), '' ),
			'meta_views_display'    => array( 'checkbox_control', __( 'Meta views display', 'wpgen' ), '' ),
			'meta_edit_display'     => array( 'checkbox_control', __( 'Meta edit display', 'wpgen' ), '' ),

			'detail'                => array( 'checkbox_control', __( 'Detail settings', 'wpgen' ), '' ),
			'detail_description'    => array( 'select_control', __( 'Select description', 'wpgen' ), '', $archive_page_detail_description_select ),
			'detail_button'         => array( 'select_control', __( 'Select button type', 'wpgen' ), '', $archive_page_detail_button_select ),
		);

		// Other options.
		$wpgen_controls['other'] = array(
			'tab_social_list'     => array( 'tab_title', __( 'Social List', 'wpgen' ), $other_tab_social_list_description ),
			'vkontakte'           => array( 'url_control', __( 'Vkontakte link', 'wpgen' ), '' ),
			'facebook'            => array( 'url_control', __( 'Facebook link', 'wpgen' ), '' ),
			'instagram'           => array( 'url_control', __( 'Instagram link', 'wpgen' ), '' ),
			'youtube'             => array( 'url_control', __( 'Youtube link', 'wpgen' ), '' ),
			'twitter'             => array( 'url_control', __( 'Twitter link', 'wpgen' ), '' ),
			'telegram'            => array( 'url_control', __( 'Telegram link', 'wpgen' ), '' ),
			'linkedin'            => array( 'url_control', __( 'Linkedin link', 'wpgen' ), '' ),

			'tab_contacts_list'   => array( 'tab_title', __( 'Contacts List', 'wpgen' ), $other_tab_contacts_list_description ),
			'address'             => array( 'text_control', __( 'Address', 'wpgen' ), '' ),
			'phone'               => array( 'text_control', __( 'Phone', 'wpgen' ), '' ),
			'email'               => array( 'text_control', __( 'Email', 'wpgen' ), '' ),
			'whatsapp_phone'      => array( 'text_control', __( 'Whatsapp phone', 'wpgen' ), $other_tab_whatsapp_phone_description ),
			'viber_phone'         => array( 'text_control', __( 'Viber', 'wpgen' ), $other_tab_viber_phone_description ),
			'telegram_nick'       => array( 'text_control', __( 'Telegram nick', 'wpgen' ), $other_tab_telegram_nick_description ),

			'tab_verification'    => array( 'tab_title', __( 'Verifications and counters', 'wpgen' ), '' ),
			'yandex_verification' => array( 'text_control', __( 'Yandex', 'wpgen' ), $other_tab_yandex_verification_description ),
			'google_verification' => array( 'text_control', __( 'Google', 'wpgen' ), $other_tab_google_verification_description ),
			'mailru_verification' => array( 'text_control', __( 'Mail ru', 'wpgen' ), $other_tab_mailru_verification_description ),

			'yandex_counter'      => array( 'text_control', __( 'Yandex', 'wpgen' ), $other_tab_yandex_counter_description ),
			'google_counter'      => array( 'text_control', __( 'Google', 'wpgen' ), $other_tab_google_counter_description ),
			'mailru_counter'      => array( 'text_control', __( 'Mail ru', 'wpgen' ), $other_tab_mailru_counter_description ),
		);

		// Merge child and parent controls.
		$wpgen_controls = apply_filters( 'wpgen_filter_controls', $wpgen_controls );

		// Return controls.
		if ( is_null( $control ) ) {
			return $wpgen_controls;
		} elseif ( ! isset( $wpgen_controls[ $control ] ) || empty( $wpgen_controls[ $control ] ) ) {
			return false;
		} else {
			return $wpgen_controls[ $control ];
		}
	}
}

/*
// Usage:
add_filter( 'wpgen_filter_controls','child_theme_filter_controls' );
function child_theme_filter_controls( $wpgen_controls ) {

	$wpgen_controls['other']['new_control'] = array( 'checkbox_control', __( 'New checkbox control', 'wpgen' ), '' );

	return $wpgen_controls;
}
*/