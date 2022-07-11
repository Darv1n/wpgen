<?php
/**
 * Main setup options
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wpgen_setup' ) ) {

	/**
	 * Default theme setup on after_setup_theme hook.
	 */
	function wpgen_setup() {

		// Make theme available for translation.
		load_theme_textdomain( 'wpgen', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'wpgen' ),
		) );

		// Switch default core markup for search form, comment form, and comments.
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
		) );

		// Add support post formats.
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'wpgen_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Set the content width in pixels, based on the theme's design and stylesheet.
		$GLOBALS['content_width'] = apply_filters( 'wpgen_content_width', 960 );

		// Add support for core custom logo.
		add_theme_support( 'custom-logo', array(
			'height'      => 180,
			'width'       => 270,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Добавляем поддержку кастом header.
		add_theme_support( 'custom-header', apply_filters( 'wpgen_custom_header_args', array(
			'default-image'      => '',
			'default-text-color' => '000000',
			'width'              => 1920,
			'height'             => 500,
			'flex-width'         => true,
			'flex-height'        => true,
		) ) );

		// Gutenberg Embeds.
		add_theme_support( 'responsive-embeds' ); 

		// Добавляем поддержку шорткодов в виджетах.
		add_filter( 'widget_text', 'do_shortcode' );

		// Убираем пустые теги.
		remove_filter( 'the_excerpt', 'wpautop' );
		remove_filter( 'the_content', 'wpautop' );
		add_filter( 'the_content', 'wpautop', 12 );

		// Добавляем троеточие в excerpt.
		add_filter( 'excerpt_more', function( $more ) {
			return '...';
		});

		// Скрываем админ панель.
		add_filter( 'show_admin_bar', '__return_false' );

		// Убираем meta generator.
		add_filter( 'the_generator', '__return_empty_string' );
		remove_action( 'wp_head', 'wp_generator' );

		// Удаляем фиды.
		remove_action( 'wp_head', 'feed_links', 2 ); // ссылки основных фидов (записи, комментарии, лента новостей).
		remove_action( 'wp_head', 'feed_links_extra', 3 ); // ссылки на доп. фиды (на рубрики, теги, таксономии).

		// Удаляем RSD, WLW ссылки, на главную, предыдущую, первую запись.
		remove_action( 'wp_head', 'rsd_link' ); // cсылка для блог-клиентов.
		remove_action( 'wp_head', 'wlwmanifest_link' ); // cсылка используемая Windows Live Writer.
		remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // ссылка на следующий и предыдущий пост.
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		remove_action( 'wp_head', 'index_rel_link' ); // ссылка на главную.
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // ссылка на первый пост.
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // ссылка на родительскую страницу.
		remove_action( 'wp_head', 'wp_resource_hints', 2 ); // удаляем dns-prefetch.

		// Отключаем emoji.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		// удаляем "Рубрика: ", "Метка: " и т.д. из заголовка архива.
		add_filter( 'get_the_archive_title', function( $title ) {
			$title = wp_strip_all_tags( $title ); // удаляем лишний span.
			return preg_replace( '~^[^:]+: ~', '', $title );
		});

		// убираем ссылку на https://ru.wordpress.org/ в авторизации.
		add_filter( 'login_headerurl', function() {
			return home_url(); // или любой другой адрес.
		});
	}
}
add_action( 'after_setup_theme', 'wpgen_setup' );



if ( ! function_exists( 'wpgen_print_root_styles' ) ) {

	/**
	 * Output a string of root styles in wp_head.
	 */
	function wpgen_print_root_styles() {

		$root_styles = get_root_styles();

		$root_string = '';
		foreach ( $root_styles as $key => $root_value ) {
			$root_string .= '--' . $key . ': ' . $root_value . ';';
		}

		echo '<style id="wpgen-root">:root {' . esc_attr( $root_string ) . '}</style>';
	}
}
add_action( 'wp_head', 'wpgen_print_root_styles', 1 );



if ( ! function_exists( 'wpgen_scripts' ) ) {

	/**
	 * Connecting general styles and scripts.
	 */
	function wpgen_scripts() {
		// Стандартный файл стилей с инфой о теме. Не используется для css из-за не удобной компиляции.
		// wp_enqueue_style( 'wpgen-style', get_stylesheet_uri(), array(), filemtime( get_theme_file_path( '/style.css' ) ) );

		// Шрифты (пытаемся получить их из опций, которые сгенерил wpgen или берем дефолтные).
		$fonts = get_default_fonts();

		if ( ! is_wpgen_active() && $fonts['primary'] === $fonts['secondary'] ) {
			wp_enqueue_style( 'primary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['primary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
		} else {
			wp_enqueue_style( 'primary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['primary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
			wp_enqueue_style( 'secondary-font', '//fonts.googleapis.com/css2?family=' . str_replace( '\'', '', str_replace( ' ', '+', $fonts['secondary'] ) ) . ':wght@400;700&display=swap', array(), '1.0.0' );
		}

		// Сетка Бутстрап.
		wp_enqueue_style( 'bootstrap-grid', get_theme_file_uri( 'assets/css/bootstrap-grid.min.css' ), array(), filemtime( get_theme_file_path( '/assets/css/bootstrap-grid.min.css' ) ) );

		// Основные стили. Компиляция галпом. Могут быть переопределены в дочерней.
		wp_enqueue_style( 'common-styles', get_theme_file_uri( 'assets/css/common.min.css' ), array(), filemtime( get_theme_file_path( '/assets/css/common.min.css' ) ) );

		// Основные скрипты. Компиляция галпом. Могут быть переопределены в дочерней.
		wp_enqueue_script( 'common-scripts', get_theme_file_uri( 'assets/js/common.min.js' ), array( 'jquery' ), filemtime( get_theme_file_path( '/assets/js/common.min.js' ) ), true );

		// Magnific.
		wp_register_script( 'magnific-scripts', get_theme_file_uri( '/assets/libs/magnific-popup/jquery.magnific-popup.min.js' ), array( 'jquery' ), null, true );

		// Комментарии.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Отключаем глобальные стили для гутенберга, если пользователь не залогинен.
		if ( ! is_user_logged_in() ) {
			wp_dequeue_style( 'global-styles' );
		}

		// Скрипты и стили для формы wpgen, если она активна.
		if ( is_wpgen_active() ) {

			wp_enqueue_style( 'wpgen-styles', get_theme_file_uri( 'assets/css/wpgen-style.min.css' ), array(), filemtime( get_theme_file_path( '/assets/css/wpgen-style.min.css' ) ) );

			wp_enqueue_script( 'ajax-wpgen', get_theme_file_uri( 'assets/js/source/wpgen.js' ), array( 'jquery' ), filemtime( get_theme_file_path( '/assets/js/source/wpgen.js' ) ), true );

			// Используем функцию wp_localize_script для передачи переменных в JS скрипт.
			wp_localize_script(
				'ajax-wpgen',
				'ajax_wpgen_obj',
				array(
					'url'   => admin_url( 'admin-ajax.php' ),
					'value' => get_selected_value(), // Локализуем массив со значениями.
					'nonce' => wp_create_nonce( 'nonce-wpgen' ), // Создаем nonce.
				)
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'wpgen_scripts' );



if ( ! function_exists( 'wpgen_widgets_init' ) ) {

	/**
	 * Register widget area.
	 */
	function wpgen_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'wpgen' ),
			'id'            => 'sidebar',
			'description'   => esc_html__( 'Add widgets in left sidebar', 'wpgen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		) );
		if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar Right', 'wpgen' ),
				'id'            => 'sidebar-right',
				'description'   => esc_html__( 'Add widgets in right sidebar', 'wpgen' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
		}

		if ( wpgen_options( 'general_top_bar_display' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Header top bar left', 'wpgen' ),
				'id'            => 'sidebar-top-left',
				'description'   => esc_html__( 'Header top sidebar left', 'wpgen' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Header top bar right', 'wpgen' ),
				'id'            => 'sidebar-top-right',
				'description'   => esc_html__( 'Header top sidebar right', 'wpgen' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
		}

		if ( wpgen_options( 'general_bottom_bar_display' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Footer bottom bar left', 'wpgen' ),
				'id'            => 'sidebar-footer-bottom-left',
				'description'   => esc_html__( 'Footer bottom sidebar left', 'wpgen' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Footer bottom bar right', 'wpgen' ),
				'id'            => 'sidebar-footer-bottom-right',
				'description'   => esc_html__( 'Footer bottom sidebar right', 'wpgen' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
		}

		if ( in_array( wpgen_options( 'general_footer_type' ), array( 'footer-three-columns', 'footer-four-columns' ), true ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'First footer sidebar', 'wpgen' ),
				'id'            => 'sidebar-footer-one',
				'description'   => esc_html__( 'First footer sidebar', 'wpgen' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Second footer sidebar', 'wpgen' ),
				'id'            => 'sidebar-footer-two',
				'description'   => esc_html__( 'Second footer sidebar', 'wpgen' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
			register_sidebar( array(
				'name'          => esc_html__( 'Third footer sidebar', 'wpgen' ),
				'id'            => 'sidebar-footer-three',
				'description'   => esc_html__( 'Third footer sidebar', 'wpgen' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
		}

		if ( wpgen_options( 'general_footer_type' ) === 'footer-four-columns' ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Fourth footer sidebar', 'wpgen' ),
				'id'            => 'sidebar-footer-four',
				'description'   => esc_html__( 'Fourth footer sidebar', 'wpgen' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			) );
		}
	}
}
add_action( 'widgets_init', 'wpgen_widgets_init' );



if ( ! function_exists( 'unset_intermediate_image_sizes' ) ) {

	/**
	 * Function for `intermediate_image_sizes` filter-hook.
	 * 
	 * @param string[] $default_sizes An array of intermediate image size names.
	 *
	 * @return string[]
	 */
	function unset_intermediate_image_sizes( $sizes ) {

		// Sizes to be removed.
		$unset_sizes = array(
			'thumbnail',
			'medium_large',
			'1536x1536',
			'2048x2048',
		);

		return array_diff( $sizes, $unset_sizes );

	}
}
add_filter( 'intermediate_image_sizes', 'unset_intermediate_image_sizes' );



if ( ! function_exists( 'remove_nav_menu_item_id' ) ) {

	/**
	 * Function for `nav_menu_item_id` filter-hook.
	 * 
	 * @param string   $menu_id   The ID that is applied to the menu item's `<li>` element.
	 * @param WP_Post  $menu_item The current menu item.
	 * @param stdClass $args      An object of wp_nav_menu() arguments.
	 * @param int      $depth     Depth of menu item. Used for padding.
	 *
	 * @return string
	 */
	function remove_nav_menu_item_id( $id, $item, $args ) {
	    return '';
	}
}
add_filter( 'nav_menu_item_id', 'remove_nav_menu_item_id', 10, 3 );



if ( ! function_exists( 'remove_nav_menu_item_class' ) ) {

	/**
	 * Function for `nav_menu_css_class` filter-hook.
	 * 
	 * @param string[] $classes   Array of the CSS classes that are applied to the menu item's `<li>` element.
	 * @param WP_Post  $menu_item The current menu item object.
	 * @param stdClass $args      An object of wp_nav_menu() arguments.
	 * @param int      $depth     Depth of menu item. Used for padding.
	 *
	 * @return string[]
	 */
	function remove_nav_menu_item_class( $classes, $item, $args ) {

		foreach ( $classes as $key => $class ) {
			if ( ! in_array( $class, array( 'menu-item', 'current-menu-item' ), true ) ) {
				unset( $classes[ $key ] ); 
			}
		}

		return $classes;
	}
}
add_filter( 'nav_menu_css_class', 'remove_nav_menu_item_class', 10, 3 );


// TGM Plugin Activation Class.
require_once get_parent_theme_file_path( '/includes/plugin-additions/class-tgm-plugin-activation.php' );

if ( ! function_exists( 'wpgen_register_recommended_plugins' ) ) {

	/**
	 * Register recommended plugins with TGM Plugin Activation
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
				'source'       => get_template_directory_uri() . '/includes/plugin-additions/tgm/kama-postviews.zip',
				'external_url' => 'https://wp-kama.ru/id_55/schitaem-kolichestvo-posescheniy-stranits-na-wordpress.html',
			),
			array(
				'name'         => 'Carbon Fields', // The plugin name.
				'slug'         => 'carbon-fields', // The plugin slug (typically the folder name).
				'required'     => false,
				'source'       => get_template_directory_uri() . '/includes/plugin-additions/tgm/carbon-fields.zip',
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
add_action( 'tgmpa_register', 'wpgen_register_recommended_plugins' );



if ( ! function_exists( 'wpgen_search_highlight' ) ) {

	/**
	 * Highlight search results.
	 *
	 * @param string $text is text for highlight.
	 *
	 * @return string
	 */
	function wpgen_search_highlight( $text ) {

		$s = get_query_var( 's' );

		if ( is_search() && in_the_loop() && ! empty( $s ) ) :

			$style       = 'background-color:#307FE2;color:#fff;font-weight:bold;';
			$query_terms = get_query_var( 'search_terms' );

			if ( ! empty( $query_terms ) ) {
				$query_terms = explode( ' ', $s );
			}
			if ( empty( $query_terms ) ) {
				return '';
			}

			foreach ( $query_terms as $term ) {
				$term  = preg_quote( $term, '/' ); // like in search string.
				$term1 = mb_strtolower( $term ); // lowercase.
				$term2 = mb_strtoupper( $term ); // uppercase.
				$term3 = mb_convert_case( $term, MB_CASE_TITLE, 'UTF-8' ); // capitalise.
				$term4 = mb_strtolower( mb_substr( $term, 0, 1 ) ) . mb_substr( $term2, 1 ); // first lowercase.
				$text  = preg_replace( "@(?<!<|</)($term|$term1|$term2|$term3|$term4)@i", "<span style=\"{$style}\">$1</span>", $text );
			}

		endif; // is_search.

		return $text;

	}
}
add_filter( 'the_title', 'wpgen_search_highlight' );
add_filter( 'the_content', 'wpgen_search_highlight' );
add_filter( 'the_excerpt', 'wpgen_search_highlight' );
