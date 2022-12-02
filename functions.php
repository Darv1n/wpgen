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
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/template-functions.php';
require_once get_template_directory() . '/inc/template-filters.php';
require_once get_template_directory() . '/inc/template-actions.php';
require_once get_template_directory() . '/inc/template-parts.php';
require_once get_template_directory() . '/inc/template-wrappers.php';
require_once get_template_directory() . '/inc/shortcodes.php';

// Customizer.
require_once get_template_directory() . '/inc/customizer/customizer.php';
require_once get_template_directory() . '/inc/customizer/customizer-controls.php';
require_once get_template_directory() . '/inc/customizer/customizer-defaults.php';

// Addons.
require_once get_template_directory() . '/inc/addons/init-gallery.php';
require_once get_template_directory() . '/inc/addons/init-media.php';
require_once get_template_directory() . '/inc/addons/init-youtube.php';
require_once get_template_directory() . '/inc/addons/init-form.php';
require_once get_template_directory() . '/inc/addons/init-slider.php';
require_once get_template_directory() . '/inc/addons/init-elems.php';

// TGM Plugin Activation.
require_once get_template_directory() . '/inc/addons/tgm/class-tgm-plugin-activation.php';
require_once get_template_directory() . '/inc/addons/tgm/tgm-setup.php';

// Root Styles.
require_once get_template_directory() . '/inc/addons/root-styles/root-styles-functions.php';
require_once get_template_directory() . '/inc/addons/root-styles/root-styles-converter.php';
require_once get_template_directory() . '/inc/addons/root-styles/root-styles-defaults.php';
require_once get_template_directory() . '/inc/addons/root-styles/root-styles-frontend.php';
require_once get_template_directory() . '/inc/addons/root-styles/root-styles.php';

// WPGen.
require_once get_template_directory() . '/inc/addons/wpgen/wpgen-customizer.php';
require_once get_template_directory() . '/inc/addons/wpgen/wpgen-root-styles.php';
require_once get_template_directory() . '/inc/addons/wpgen/wpgen-frontend-form.php';
require_once get_template_directory() . '/inc/addons/wpgen/wpgen-ajax-handler.php';

// Lib for DOM parsing https://simplehtmldom.sourceforge.io/
if ( ! class_exists( 'simple_html_dom_node' ) ) {
	require_once get_template_directory() . '/inc/libs/simple-html-dom.php';
}

// Lib for Excel import https://github.com/shuchkin/simplexlsx/
if ( ! class_exists( 'SimpleXLSX' ) ) {
	require_once get_template_directory() . '/inc/libs/simplexlsx.php';
}

// Lib for Excel export https://github.com/shuchkin/simplexlsxgen/
if ( ! class_exists( 'SimpleXLSXGen' ) ) {
	require_once get_template_directory() . '/inc/libs/simplexlsxgen.php';
}

// Yoast SEO.
if ( is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
	require_once get_template_directory() . '/inc/compatibility/yoast.php';
}

// WooCommerce.
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	// require_once get_template_directory() . '/inc/compatibility/woocommerce/setup.php';
	// require_once get_template_directory() . '/inc/compatibility/woocommerce/template-functions.php';
	// require_once get_template_directory() . '/inc/compatibility/woocommerce/template-wrappers.php';
}
