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

// TGM Plugin Activation Class.
require_once get_template_directory() . '/includes/plugin-additions/class-tgm-plugin-activation.php';

// Setup.
require_once get_template_directory() . '/includes/setup.php';

// Customizer.
require_once get_template_directory() . '/includes/customizer/customizer.php';
require_once get_template_directory() . '/includes/customizer/customizer-controls.php';
require_once get_template_directory() . '/includes/customizer/customizer-defaults.php';

// Backend.
require_once get_template_directory() . '/includes/backend/functions.php';
require_once get_template_directory() . '/includes/backend/pre-get-posts.php';
require_once get_template_directory() . '/includes/backend/save-post.php';

// Frontend.
require_once get_template_directory() . '/includes/template-parts.php';
require_once get_template_directory() . '/includes/template-functions.php';
require_once get_template_directory() . '/includes/template-wrappers.php';
require_once get_template_directory() . '/includes/frontend/the-content.php';

// Init.
require_once get_template_directory() . '/includes/init/init-gallery.php';
require_once get_template_directory() . '/includes/init/init-media.php';
require_once get_template_directory() . '/includes/init/init-youtube.php';
require_once get_template_directory() . '/includes/init/init-form.php';
require_once get_template_directory() . '/includes/init/init-slider.php';
require_once get_template_directory() . '/includes/init/init-elems.php';

// Root Styles.
require_once get_template_directory() . '/includes/root-styles/root-styles-functions.php';
require_once get_template_directory() . '/includes/root-styles/root-styles-converter.php';
require_once get_template_directory() . '/includes/root-styles/root-styles-defaults.php';
require_once get_template_directory() . '/includes/root-styles/root-styles-frontend.php';
require_once get_template_directory() . '/includes/root-styles/root-styles.php';

// Wpgen.
require_once get_template_directory() . '/includes/wpgen/wpgen-customizer.php';
require_once get_template_directory() . '/includes/wpgen/wpgen-root-styles.php';
require_once get_template_directory() . '/includes/wpgen/wpgen-frontend-form.php';
require_once get_template_directory() . '/includes/wpgen/wpgen-ajax-handler.php';

// Shortcodes.
require_once get_template_directory() . '/includes/shortcodes.php';

// Lib for DOM parsing https://simplehtmldom.sourceforge.io/
if ( ! class_exists( 'simple_html_dom_node' ) ) {
	require_once get_template_directory() . '/includes/plugin-additions/simple-html-dom.php';
}

// Lib for Excel import https://github.com/shuchkin/simplexlsx/
if ( ! class_exists( 'SimpleXLSX' ) ) {
	require_once get_template_directory() . '/includes/plugin-additions/simplexlsx.php';
}

// Lib for Excel export https://github.com/shuchkin/simplexlsxgen/
if ( ! class_exists( 'SimpleXLSXGen' ) ) {
	require_once get_template_directory() . '/includes/plugin-additions/simplexlsxgen.php';
}

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
