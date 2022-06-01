<?php
/**
 * Wpgen Theme Customizer
 *
 * @package wpgen
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wpgen_customize_register( $wp_customize ) {

	/**
	 * Control Note.
	 */
	class wpgen_Customize_Control extends WP_Customize_Control {

		public function render_content() {
			echo '<span>' . esc_html( $this->label ) . '</span>';
		}
	}

	/**
	 * Sanitize checkbox.
	 */
	function wpgen_sanitize_checkbox( $input ) {
		return $input ? true : false;
	}

	/**
	 * Sanitize select.
	 */
	function wpgen_sanitize_select( $input, $setting ) {
		// Get all select options.
		$options = $setting->manager->get_control( $setting->id )->choices;
		// Return default if not valid.
		return ( array_key_exists( $input, $options ) ? $input : $setting->default );
	}

	/**
	 * Sanitize custom controls.
	 */
	function wpgen_sanitize_custom_control( $input ) {
		return $input;
	}

	/**
	 * Sanitize number absint.
	 */
	function wpgen_sanitize_number_control( $number, $setting ) {

		// ensure $number is an absolute integer.
		$number = absint( $number );

		// return default if not integer.
		return ( $number ? $number : $setting->default );

	}

	/**
	 * Sanitize textarea.
	 */
	function wpgen_sanitize_textarea( $input ) {

		$allowedtags = array(
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'_blank' => array(),
			),
			'img'    => array(
				'src'    => array(),
				'alt'    => array(),
				'width'  => array(),
				'height' => array(),
				'style'  => array(),
				'class'  => array(),
				'id'     => array(),
			),
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'script' => array(),
		);

		// return filtered html.
		return esc_html( $input );

	}

	// Common functions for reusable options.
	/**
	 * Number.
	 */
	function wpgen_number_control( $section, $id, $name, $atts, $priority ) {
		global $wp_customize;

		$wp_customize->add_setting( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'default'           => wpgen_options( $section . '_' . $id ),
			'type'              => 'option',
			'transport'         => 'postMessage',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'wpgen_sanitize_number_control',
		) );
		$wp_customize->add_control( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'label'       => $name,
			'section'     => 'wpgen_' . $section,
			'type'        => 'number',
			'input_attrs' => $atts,
			'priority'    => $priority,
		) );
	}

	/**
	 * Title.
	 */
	function wpgen_tab_title( $section, $id, $name, $description, $priority ) {
		global $wp_customize;

		$wp_customize->add_setting( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'type'      => 'theme_mod',
			'transport' => 'postMessage',
		) );
		$wp_customize->add_control( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'label'       => $name,
			'section'     => 'wpgen_' . $section,
			'type'        => 'hidden',
			'description' => $description,
			'priority'    => $priority,
		) );
	}

	/**
	 * Text.
	 */
	function wpgen_text_control( $section, $id, $name, $description, $priority ) {
		global $wp_customize;

		$wp_customize->add_setting( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'default'           => wpgen_options( $section . '_' . $id ),
			'type'              => 'option',
			'transport'         => 'refresh',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'label'       => $name,
			'description' => wp_kses_post( $description ),
			'section'     => 'wpgen_' . $section,
			'type'        => 'text',
			'priority'    => $priority,
		) );
	}

	/**
	 * Textarea.
	 */
	function wpgen_textarea_control( $section, $id, $name, $description, $priority ) {
		global $wp_customize;

		$wp_customize->add_setting( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'default'           => wpgen_options( $section . '_' . $id ),
			'type'              => 'option',
			'transport'         => 'refresh',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_html',
		) );
		$wp_customize->add_control( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'label'       => $name,
			'description' => wp_kses_post( $description ),
			'section'     => 'wpgen_' . $section,
			'type'        => 'textarea',
			'priority'    => $priority,
		) );
	}

	/**
	 * Url.
	 */
	function wpgen_url_control( $section, $id, $name, $description, $priority ) {
		global $wp_customize;
		$wp_customize->add_setting( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'default'           => wpgen_options( $section . '_' . $id ),
			'type'              => 'option',
			'transport'         => 'refresh',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		) );
		$wp_customize->add_control( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'label'       => $name,
			'description' => wp_kses_post( $description ),
			'section'     => 'wpgen_' . $section,
			'type'        => 'text',
			'priority'    => $priority,
		) );
	}

	/**
	 * Checkbox.
	 */
	function wpgen_checkbox_control( $section, $id, $name, $description, $priority ) {
		global $wp_customize;

		$wp_customize->add_setting( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'default'           => wpgen_options( $section . '_' . $id ),
			'type'              => 'option',
			'transport'         => 'refresh',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'wpgen_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'label'       => $name,
			'description' => wp_kses_post( $description ),
			'section'     => 'wpgen_' . $section,
			'type'        => 'checkbox',
			'priority'    => $priority,
		) );
	}

	/**
	 * Select.
	 */
	function wpgen_select_control( $section, $id, $name, $description, $atts, $priority ) {
		global $wp_customize;
		$wp_customize->add_setting( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'default'           => wpgen_options( $section . '_' . $id ),
			'type'              => 'option',
			'transport'         => 'refresh',
			'capability'        => 'edit_theme_options',
			'sanitize_callback'	=> 'wpgen_sanitize_select',
		) );
		$wp_customize->add_control( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'label'       => $name,
			'description' => wp_kses_post( $description ),
			'section'     => 'wpgen_' . $section,
			'type'        => 'select',
			'choices'     => $atts,
			'priority'    => $priority,
		) );
	}

	/**
	 * Radio.
	 */
	function wpgen_radio_control( $section, $id, $name, $description, $atts, $priority ) {
		global $wp_customize;

		$wp_customize->add_setting( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'default'           => wpgen_options( $section . '_' . $id ),
			'type'              => 'option',
			'transport'         => 'refresh',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'wpgen_sanitize_select',
		) );
		$wp_customize->add_control( 'wpgen_options[' . $section . '_' . $id . ']', array(
			'label'       => $name,
			'description' => wp_kses_post( $description ),
			'section'     => 'wpgen_' . $section,
			'type'        => 'radio',
			'choices'     => $atts,
			'priority'    => $priority,
		) );
	}

	// Add Sections.
	$wp_customize->add_section( 'wpgen_general', array(
		'title'      => __( 'General options', 'wpgen' ),
		'priority'   => 15,
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_section( 'wpgen_sidebar', array(
		'title'      => __( 'Sidebar options', 'wpgen' ),
		'priority'   => 20,
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_section( 'wpgen_front_page', array(
		'title'      => __( 'Front page options', 'wpgen' ),
		'priority'   => 22,
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_section( 'wpgen_single_post', array(
		'title'      => __( 'Single post options', 'wpgen' ),
		'priority'   => 24,
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_section( 'wpgen_archive_page', array(
		'title'      => __( 'Archive options', 'wpgen' ),
		'priority'   => 26,
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_section( 'wpgen_other', array(
		'title'      => __( 'Other options', 'wpgen' ),
		'priority'   => 28,
		'capability' => 'edit_theme_options',
	) );

	// Add atts.
	$general_color_scheme = array(
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
	$general_menu_align_select                 = __( 'Choose which side to display the menu', 'wpgen' );
	$general_menu_position_description         = __( 'Position of the menu container when opened', 'wpgen' );
	$general_menu_align_description            = __( 'Alignment of the menu container', 'wpgen' );
	$general_wpgen_active                      = __( 'This checkbox activates the WpGen form in the frontend for the site administrator', 'wpgen' );
	$general_top_bar_description               = __( 'This checkbox displays two sidebars at the header of the site. They are adds in the widget section options', 'wpgen' );
	$general_bottom_bar_description            = __( 'This checkbox displays two sidebars at the footer of the site. They are adds in the widget section options', 'wpgen' );
	$general_cookie_display_description        = __( 'Displays a notification about the use of cookies on the site', 'wpgen' );
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

	// Add options.
	// Добавляем опции.
	// #General.
	// wpgen_tab_title( 'general', 'theme', __( 'Fonts', 'wpgen' ), '', 10 );
	// wpgen_select_control( 'general', 'primary_font', __( 'Primary font', 'wpgen' ), '', get_selected_font(), 12 );
	// wpgen_select_control( 'general', 'secondary_font', __( 'Secondary font', 'wpgen' ), '', get_selected_font(), 14 );

	wpgen_tab_title( 'general', 'content_title', __( 'Content', 'wpgen' ), '', 20 );
	wpgen_checkbox_control( 'general', 'wpgen_active', __( 'Top bar display', 'wpgen' ), $general_wpgen_active, 21 );
	wpgen_select_control( 'general', 'color_scheme', __( 'Content color scheme', 'wpgen' ), '', $general_color_scheme, 22 );
	wpgen_select_control( 'general', 'container_width', __( 'Select container width', 'wpgen' ), $general_container_width_description, $general_container_width_select, 24 );
	wpgen_select_control( 'general', 'content_width', __( 'Select content width', 'wpgen' ), $general_content_width_description, $general_content_width_select, 26 );
	wpgen_select_control( 'general', 'button_type', __( 'Select button type', 'wpgen' ), '', $general_button_type_select, 28 );

	wpgen_tab_title( 'general', 'header_title', __( 'Header', 'wpgen' ), '', 30 );
	wpgen_checkbox_control( 'general', 'top_bar_display', __( 'Top bar display', 'wpgen' ), $general_top_bar_description, 32 );
	wpgen_select_control( 'general', 'header_color_scheme', __( 'Header color scheme', 'wpgen' ), '', $general_color_scheme, 34 );
	wpgen_select_control( 'general', 'header_type', __( 'Select header type', 'wpgen' ), '', $general_header_type_select, 36 );

	wpgen_checkbox_control( 'general', 'menu_display', __( 'Menu display', 'wpgen' ), '', 40 );
	wpgen_select_control( 'general', 'menu_type', __( 'Select menu type', 'wpgen' ), '', $general_menu_type_select, 42 );
	wpgen_select_control( 'general', 'menu_color_scheme', __( 'Menu color scheme', 'wpgen' ), '', $general_color_scheme, 44 );
	wpgen_select_control( 'general', 'menu_position', __( 'Select menu position', 'wpgen' ), $general_menu_position_description, $general_menu_position_description, 46 );
	wpgen_select_control( 'general', 'menu_align', __( 'Select menu alignment', 'wpgen' ), $general_menu_align_description, $general_menu_align_select, 48 );
	wpgen_select_control( 'general', 'menu_button_alignment', __( 'Select menu button alignment', 'wpgen' ), '', $general_menu_button_align_select, 42 );
	wpgen_select_control( 'general', 'menu_button_type', __( 'Select menu button type', 'wpgen' ), '', $general_menu_button_type_select, 42 );

	wpgen_tab_title( 'general', 'footer_title', __( 'Footer', 'wpgen' ), '', 50 );
	wpgen_checkbox_control( 'general', 'bottom_bar_display', __( 'Bottom bar display', 'wpgen' ), $general_bottom_bar_description, 52 );
	wpgen_select_control( 'general', 'footer_color_scheme', __( 'Footer color scheme', 'wpgen' ), '', $general_color_scheme, 54 );
	wpgen_select_control( 'general', 'footer_type', __( 'Select footer type', 'wpgen' ), '', $general_footer_type_select, 56 );

	wpgen_checkbox_control( 'general', 'breadcrumbs_display', __( 'Breadcrumbs display', 'wpgen' ), '', 60 );
	wpgen_select_control( 'general', 'breadcrumbs', __( 'Select position', 'wpgen' ), '', $general_breadcrumbs_select, 62 );

	wpgen_checkbox_control( 'general', 'scroll_top_button_display', __( 'Scroll to top button display', 'wpgen' ), '', 70 );
	wpgen_select_control( 'general', 'scroll_top_button_alignment', __( 'Select scroll top button alignment', 'wpgen' ), '', $alignment_select, 72 );
	wpgen_select_control( 'general', 'scroll_top_button_type', __( 'Select scroll top button type', 'wpgen' ), '', $general_menu_button_type_select, 74 );

	wpgen_tab_title( 'general', 'cookie_title', __( 'Cookie', 'wpgen' ), '', 80 );
	wpgen_checkbox_control( 'general', 'cookie_display', __( 'Cookie display', 'wpgen' ), $general_cookie_display_description, 82 );

	// #Sidebar.
	wpgen_checkbox_control( 'sidebar', 'display', __( 'Sidebar display', 'wpgen' ), '', 2 );
	wpgen_select_control( 'sidebar', 'position', __( 'Select Sidebar Position', 'wpgen' ), $sidebar_position_description, $alignment_select, 5 );

	wpgen_tab_title( 'sidebar', 'tab_title', __( 'Which pages display sidebar', 'wpgen' ), '', 10 );
	wpgen_checkbox_control( 'sidebar', 'display_home', __( 'Home page', 'wpgen' ), '', 21 );
	wpgen_checkbox_control( 'sidebar', 'display_post', __( 'Single post', 'wpgen' ), '', 23 );
	wpgen_checkbox_control( 'sidebar', 'display_page', __( 'Single page', 'wpgen' ), '', 25 );
	wpgen_checkbox_control( 'sidebar', 'display_archive', __( 'Archive page', 'wpgen' ), '', 27 );
	wpgen_checkbox_control( 'sidebar', 'display_search', __( 'Search page', 'wpgen' ), '', 29 );
	wpgen_checkbox_control( 'sidebar', 'display_error', __( '404 page', 'wpgen' ), '', 31 );
	wpgen_checkbox_control( 'sidebar', 'display_author', __( 'Author page', 'wpgen' ), '', 33 );

	wpgen_checkbox_control( 'sidebar', 'left_display', __( 'Left Sidebar Display', 'wpgen' ), '', 40 );
	wpgen_checkbox_control( 'sidebar', 'right_display', __( 'Right Sidebar Display', 'wpgen' ), '', 41 );

	// #Single Post.
	wpgen_checkbox_control( 'single_post', 'meta_display', __( 'Meta display', 'wpgen' ), '', 2 );
	wpgen_select_control( 'single_post', 'template_type', __( 'Select Template Type', 'wpgen' ), $single_post_template_type_description, $single_post_template_type_select, 5 );
	wpgen_checkbox_control( 'single_post', 'meta_author_display', __( 'Meta author display', 'wpgen' ), '', 10 );
	wpgen_checkbox_control( 'single_post', 'meta_date_display', __( 'Meta publish date display', 'wpgen' ), '', 11 );
	wpgen_checkbox_control( 'single_post', 'meta_cats_display', __( 'Meta categoties display', 'wpgen' ), '', 12 );
	wpgen_checkbox_control( 'single_post', 'meta_tags_display', __( 'Meta tags display', 'wpgen' ), '', 13 );
	wpgen_checkbox_control( 'single_post', 'meta_comments_display', __( 'Meta comments display', 'wpgen' ), '', 14 );
	wpgen_checkbox_control( 'single_post', 'meta_time_display', __( 'Meta read time display', 'wpgen' ), '', 15 );
	wpgen_checkbox_control( 'single_post', 'meta_views_display', __( 'Meta views display', 'wpgen' ), '', 16 );
	wpgen_checkbox_control( 'single_post', 'meta_edit_display', __( 'Meta edit display', 'wpgen' ), '', 17 );

	wpgen_tab_title( 'single_post', 'tab_title', __( 'Post options', 'wpgen' ), '', 20 );
	wpgen_checkbox_control( 'single_post', 'date_modified_display', __( 'Date modified display', 'wpgen' ), '', 22 );
	wpgen_checkbox_control( 'single_post', 'thumbnail_display', __( 'Thumbnail display', 'wpgen' ), '', 24 );
	wpgen_checkbox_control( 'single_post', 'similar_posts_display', __( 'Similar posts display', 'wpgen' ), '', 26 );
	wpgen_select_control( 'single_post', 'similar_posts_orderby', __( 'Select posts orderby', 'wpgen' ), '', $single_post_similar_posts_orderby_select, 28 );
	wpgen_number_control( 'single_post', 'similar_posts_count', __( 'Select posts count', 'wpgen' ), array( 'step' => '1' ), 30 );

	wpgen_checkbox_control( 'single_post', 'entry_footer_display', __( 'Entry Footer display', 'wpgen' ), '', 40 );
	wpgen_checkbox_control( 'single_post', 'entry_footer_cats_display', __( 'Entry Footer cats display', 'wpgen' ), '', 42 );
	wpgen_checkbox_control( 'single_post', 'entry_footer_tags_display', __( 'Entry Footer tags display', 'wpgen' ), '', 44 );
	wpgen_checkbox_control( 'single_post', 'post_nav_display', __( 'Prev/next post navigation display', 'wpgen' ), '', 46 );

	// wpgen_select_control( 'front_page', 'columns', __( 'Select columns of posts', 'wpgen' ), $front_page_columns_description, $archive_page_columns_select, 5 );

	// #Archive Page.
	wpgen_select_control( 'archive_page', 'columns', __( 'Select columns of posts', 'wpgen' ), $archive_page_columns_description, $archive_page_columns_select, 5 );
	wpgen_select_control( 'archive_page', 'template_type', __( 'Select template type', 'wpgen' ), $archive_page_template_type_description, $archive_page_template_type_select, 6 );
	wpgen_select_control( 'archive_page', 'pagination', __( 'Post Pagination', 'wpgen' ), '', $archive_page_pagination_select, 8 );

	wpgen_checkbox_control( 'archive_page', 'meta_display', __( 'Meta display', 'wpgen' ), '', 10 );
	wpgen_checkbox_control( 'archive_page', 'meta_author_display', __( 'Meta author display', 'wpgen' ), '', 11 );
	wpgen_checkbox_control( 'archive_page', 'meta_date_display', __( 'Meta publish date display', 'wpgen' ), '', 12 );
	wpgen_checkbox_control( 'archive_page', 'meta_cats_display', __( 'Meta categoties display', 'wpgen' ), '', 13 );
	wpgen_checkbox_control( 'archive_page', 'meta_tags_display', __( 'Meta tags display', 'wpgen' ), '', 14 );
	wpgen_checkbox_control( 'archive_page', 'meta_comments_display', __( 'Meta comments display', 'wpgen' ), '', 15 );
	wpgen_checkbox_control( 'archive_page', 'meta_time_display', __( 'Meta read time display', 'wpgen' ), '', 16 );
	wpgen_checkbox_control( 'archive_page', 'meta_views_display', __( 'Meta views display', 'wpgen' ), '', 17 );
	wpgen_checkbox_control( 'archive_page', 'meta_edit_display', __( 'Meta edit display', 'wpgen' ), '', 18 );

	wpgen_checkbox_control( 'archive_page', 'detail', __( 'Detail settings', 'wpgen' ), '', 20 );
	wpgen_select_control( 'archive_page', 'detail_description', __( 'Select description', 'wpgen' ), '', $archive_page_detail_description_select, 21 );
	wpgen_select_control( 'archive_page', 'detail_button', __( 'Select button type', 'wpgen' ), '', $archive_page_detail_button_select, 22 );

	// wpgen_checkbox_control( 'archive_page', 'alt', __( 'Alternate archive', 'wpgen' ), '', 23 );

	// #Other.
	// соц.сети.
	wpgen_tab_title( 'other', 'tab_social_list', __( 'Social List', 'wpgen' ), $other_tab_social_list_description, 10 );
	wpgen_url_control( 'other', 'vkontakte', __( 'Vkontakte link', 'wpgen' ), '', 12 );
	wpgen_url_control( 'other', 'facebook', __( 'Facebook link', 'wpgen' ), '', 13 );
	wpgen_url_control( 'other', 'instagram', __( 'Instagram link', 'wpgen' ), '', 14 );
	wpgen_url_control( 'other', 'youtube', __( 'Youtube link', 'wpgen' ), '', 15 );
	wpgen_url_control( 'other', 'twitter', __( 'Twitter link', 'wpgen' ), '', 16 );
	wpgen_url_control( 'other', 'telegram', __( 'Telegram link', 'wpgen' ), '', 17 );
	wpgen_url_control( 'other', 'linkedin', __( 'Linkedin link', 'wpgen' ), '', 18 );

	// контакты.
	wpgen_text_control( 'other', 'whatsapp_phone', __( 'Whatsapp phone', 'wpgen' ), $other_tab_whatsapp_phone_description, 20 );
	wpgen_text_control( 'other', 'viber_phone', __( 'Viber', 'wpgen' ), $other_tab_viber_phone_description, 22 );
	wpgen_text_control( 'other', 'telegram_nick', __( 'Telegram nick', 'wpgen' ), $other_tab_telegram_nick_description, 22 );

	wpgen_tab_title( 'other', 'tab_contacts_list', __( 'Contacts List', 'wpgen' ), $other_tab_contacts_list_description, 30 );
	wpgen_text_control( 'other', 'address', __( 'Address', 'wpgen' ), '', 32 );
	wpgen_text_control( 'other', 'phone', __( 'Phone', 'wpgen' ), '', 34 );
	wpgen_text_control( 'other', 'email', __( 'Email', 'wpgen' ), '', 36 );

	wpgen_tab_title( 'other', 'tab_verification', __( 'Verifications and counters', 'wpgen' ), '', 40 );
	wpgen_text_control( 'other', 'yandex_verification', __( 'Yandex', 'wpgen' ), $other_tab_yandex_verification_description, 42 );
	wpgen_text_control( 'other', 'google_verification', __( 'Google', 'wpgen' ), $other_tab_google_verification_description, 44 );
	wpgen_text_control( 'other', 'mailru_verification', __( 'Mail ru', 'wpgen' ), $other_tab_mailru_verification_description, 46 );

	wpgen_text_control( 'other', 'yandex_counter', __( 'Yandex', 'wpgen' ), $other_tab_yandex_counter_description, 50 );
	wpgen_text_control( 'other', 'google_counter', __( 'Google', 'wpgen' ), $other_tab_google_counter_description, 52 );
	wpgen_text_control( 'other', 'mailru_counter', __( 'Mail ru', 'wpgen' ), $other_tab_mailru_counter_description, 54 );

/*
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-branding__title a',
			'render_callback' => 'wpgen_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-branding__description',
			'render_callback' => 'wpgen_customize_partial_blogdescription',
		) );
	}

	$wp_customize->add_setting( 'tab-title', array(
		'sanitize_callback' => 'wpgen_sanitize_custom_control'
	) );
	$wp_customize->add_control( new wpgen_Customize_Control ( $wp_customize,
			'tab-title', array(
				'section'	=> 'wpgen_sidebar',
				'type'		=> 'text',
				'label' 	=> __( 'Which pages display sidebar?', 'wpgen' ),
				'priority'	=> 12
			)
		)
	);
	*/

}
add_action( 'customize_register', 'wpgen_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function wpgen_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function wpgen_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wpgen_customize_preview_js() {
	wp_enqueue_style( 'wpgen-customizer-ui-css', get_theme_file_uri( '/assets/css/customizer-ui.css' ) );
	wp_enqueue_script( 'wpgen-customizer', get_template_directory_uri() . '/assets/js/customizer.min.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'wpgen_customize_preview_js' );

/**
 * Load dynamic logic for the customizer controls area.
 */
function wpgen_panels_js() {
	wp_enqueue_style( 'wpgen-customizer-css', get_theme_file_uri( '/assets/css/customizer.min.css' ) );
	wp_enqueue_script( 'wpgen-customizer-js', get_theme_file_uri( '/assets/js/customize-controls.min.js' ), array(), '1.0', true );
}
add_action( 'customize_controls_enqueue_scripts', 'wpgen_panels_js' );
