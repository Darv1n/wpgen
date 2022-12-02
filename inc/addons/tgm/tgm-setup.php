<?php
/**
 * Register recommended plugins
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'tgmpa_register', 'wpgen_register_recommended_plugins' );
if ( ! function_exists( 'wpgen_register_recommended_plugins' ) ) {

	/**
	 * Register recommended plugins with TGM Plugin Activation (require class-tgm-plugin-activation.php in functions.php)
	 */
	function wpgen_register_recommended_plugins() {
		$plugins = array(
			array(
				'name'     => 'Query Monitor',
				'slug'     => 'query-monitor',
				'required' => false,
			),
			array(
				'name'     => 'Cyr-To-Lat',
				'slug'     => 'cyr2lat',
				'required' => false,
			),
			array(
				'name'     => 'WP Super Cache',
				'slug'     => 'wp-super-cache',
				'required' => false,
			),
			array(
				'name'     => 'Minify HTML',
				'slug'     => 'minify-html-markup',
				'required' => false,
			),
			array(
				'name'     => 'Schema',
				'slug'     => 'schema',
				'required' => false,
			),
			array(
				'name'     => 'Stream',
				'slug'     => 'stream',
				'required' => false,
			),
			array(
				'name'     => 'Limit Login Attempts Reloaded',
				'slug'     => 'limit-login-attempts-reloaded',
				'required' => false,
			),
			array(
				'name'     => 'Yoast',
				'slug'     => 'wordpress-seo',
				'required' => false,
			),
			array(
				'name'     => 'Advanced noCaptcha & invisible Captcha (v2 & v3)',
				'slug'     => 'advanced-nocaptcha-recaptcha',
				'required' => false,
			),
			array(
				'name'     => 'WebP Express',
				'slug'     => 'webp-express',
				'required' => false,
			),
			array(
				'name'         => 'Kama Postviews', // The plugin name.
				'slug'         => 'kama-postviews', // The plugin slug (typically the folder name).
				'required'     => false,
				'source'       => get_template_directory_uri() . '/inc/addons/tgm/zip/kama-postviews.zip',
				'external_url' => 'https://wp-kama.ru/id_55/schitaem-kolichestvo-posescheniy-stranits-na-wordpress.html',
			),
			array(
				'name'         => 'Carbon Fields', // The plugin name.
				'slug'         => 'carbon-fields', // The plugin slug (typically the folder name).
				'required'     => false,
				'source'       => get_template_directory_uri() . '/inc/addons/tgm/zip/carbon-fields.zip',
				'external_url' => 'https://carbonfields.net/',
			),
		);

		$config = array(
			'id'           => 'wpgen',                 // ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'plugins.php',           // Parent menu slug.
			'capability'   => 'manage_options',        // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		);

		tgmpa( $plugins, $config );
	}
}