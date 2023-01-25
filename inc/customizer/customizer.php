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
			echo '<span>' . get_escape_title( $this->label ) . '</span>';
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
		$controls = $setting->manager->get_control( $setting->id )->choices;
		// Return default if not valid.
		return ( array_key_exists( $input, $controls ) ? $input : $setting->default );
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

		// Ensure $number is an absolute integer.
		$number = absint( $number );

		// Return default if not integer.
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

		// Return filtered html.
		return esc_html( $input );
	}

	// Common functions for reusable options.
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


	// Add sections.
	$args = array(
		'public'   => true,
		'_builtin' => false,
	);

	$post_types = get_post_types( $args );
	array_unshift( $post_types, 'post' );

	$sections = array(
		'general'      => __( 'General options', 'wpgen' ),
		'sidebar'      => __( 'Sidebar options', 'wpgen' ),
		'front_page'   => __( 'Front page options', 'wpgen' ),
		'archive_page' => __( 'Archive options', 'wpgen' ),
	);

	foreach ( $post_types as $key => $post_type ) {
		$sections['single_' . $post_type] = __( 'Single ' . $post_type . ' options', 'wpgen' );
	}

	foreach ( $post_types as $key => $post_type ) {
		$sections['archive_' . $post_type] = __( 'Archive ' . $post_type . ' options', 'wpgen' );
	}

	$sections['other'] = __( 'Other options', 'wpgen' );

	$priority = 1;
	foreach ( $sections as $section_name => $section_title ) {
		$wp_customize->add_section( 'wpgen_' . $section_name, array(
			'title'      => $section_title,
			'priority'   => $priority,
			'capability' => 'edit_theme_options',
		) );
		$priority++;
	}

	$controls = get_wpgen_customizer_controls();

	// Set theme options.
	foreach ( $controls as $section_name => $control ) {
		$priority = 0;
		foreach ( $control as $control_name => $value ) {

			$priority++;

			switch ( $value[0] ) {
				case 'tab_title':
					wpgen_tab_title( $section_name, $control_name, $value[1], $value[2]/*description*/, $priority );
					break;
				case 'number_control':
					wpgen_number_control( $section_name, $control_name, $value[1], $value[2]/*atts*/, $priority );
					break;
				case 'text_control':
					wpgen_text_control( $section_name, $control_name, $value[1], $value[2]/*description*/, $priority );
					break;
				case 'textarea_control':
					wpgen_textarea_control( $section_name, $control_name, $value[1], $value[2]/*description*/, $priority );
					break;
				case 'url_control':
					wpgen_url_control( $section_name, $control_name, $value[1], $value[2]/*description*/, $priority );
					break;
				case 'checkbox_control':
					wpgen_checkbox_control( $section_name, $control_name, $value[1], $value[2]/*description*/, $priority );
					break;
				case 'select_control':
					wpgen_select_control( $section_name, $control_name, $value[1], $value[2]/*description*/, $value[3]/*atts*/, $priority );
					break;
				case 'radio_control':
					wpgen_radio_control( $section_name, $control_name, $value[1], $value[2]/*description*/, $value[3]/*atts*/, $priority );
					break;	
			}
		}
	}

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
