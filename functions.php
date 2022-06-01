<?php
/**
 * Wpgen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wpgen
 */

require_once ABSPATH . '/wp-admin/includes/plugin.php';
require_once ABSPATH . '/wp-admin/includes/taxonomy.php';

// Setup.
require_once get_template_directory() . '/includes/setup.php';

// Customizer.
require_once get_template_directory() . '/includes/customizer/customizer.php';
require_once get_template_directory() . '/includes/customizer/customizer-defaults.php';

// Backend.
require_once get_template_directory() . '/includes/backend/functions.php';
require_once get_template_directory() . '/includes/backend/root-defaults.php';
require_once get_template_directory() . '/includes/backend/pre-get-posts.php';
require_once get_template_directory() . '/includes/backend/save-post.php';

// Frontend.
require_once get_template_directory() . '/includes/template-parts.php';
require_once get_template_directory() . '/includes/template-functions.php';
require_once get_template_directory() . '/includes/template-wrappers.php';

// Wpgen.
require_once get_template_directory() . '/includes/wpgen/functions.php';
require_once get_template_directory() . '/includes/wpgen/converter.php';
require_once get_template_directory() . '/includes/wpgen/wpgen-customizer.php';
require_once get_template_directory() . '/includes/wpgen/wpgen-root.php';
require_once get_template_directory() . '/includes/wpgen/wpgen-form.php';
require_once get_template_directory() . '/includes/wpgen/wpgen-handler.php';

// Shortcodes.
require_once get_template_directory() . '/includes/shortcodes.php';

// Lib for DOM parsing https://simplehtmldom.sourceforge.io/
require_once get_template_directory() . '/includes/plugin-additions/simple-html-dom.php';

// Lib for Excel import https://github.com/shuchkin/simplexlsx/
if ( ! is_plugin_active( 'tablepress/tablepress.php' ) ) {
	require_once get_template_directory() . '/includes/plugin-additions/SimpleXLSX.php';
} else {
	require_once WP_PLUGIN_DIR . '/tablepress/libraries/simplexlsx.class.php';
}

// Lib for Excel export https://github.com/shuchkin/simplexlsxgen/
require_once get_template_directory() . '/includes/plugin-additions/SimpleXLSXGen.php';

// Yoast SEO.
if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
	require_once get_template_directory() . '/includes/plugin-additions/yoast.php';
}

// WooCommerce.
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	// require_once get_template_directory() . '/includes/plugin-additions/woocommerce/setup.php';
	// require_once get_template_directory() . '/includes/plugin-additions/woocommerce/template-functions.php';
	// require_once get_template_directory() . '/includes/plugin-additions/woocommerce/template-wrappers.php';
}
