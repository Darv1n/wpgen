<?php
/**
 * Main setup options
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_setup_theme', 'wpgen_setup' );
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

		// Переписываем email в нижний регистр.
		add_filter( 'sanitize_email', 'lowercase_sanitize_email' );
		function lowercase_sanitize_email( $email ) {
			return strtolower( $email );
		}

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

add_action( 'wp_enqueue_scripts', 'wpgen_scripts' );
if ( ! function_exists( 'wpgen_scripts' ) ) {

	/**
	 * Connecting general styles and scripts.
	 */
	function wpgen_scripts() {
		// Стандартный файл стилей с инфой о теме. Не используется для css из-за не удобной компиляции.
		// wp_enqueue_style( 'wpgen-style', get_stylesheet_uri(), array(), filemtime( get_theme_file_path( '/style.css' ) ) );

		// Сетка Бутстрап.
		wp_enqueue_style( 'bootstrap-grid', get_theme_file_uri( 'assets/css/bootstrap-grid.min.css' ), array(), filemtime( get_theme_file_path( '/assets/css/bootstrap-grid.min.css' ) ) );

		// Основные стили. Компиляция галпом. Могут быть переопределены в дочерней.
		wp_enqueue_style( 'common-styles', get_theme_file_uri( 'assets/css/common.min.css' ), array(), filemtime( get_theme_file_path( '/assets/css/common.min.css' ) ) );

		// Основные скрипты. Компиляция галпом. Могут быть переопределены в дочерней.
		wp_enqueue_script( 'common-scripts', get_theme_file_uri( 'assets/js/common.min.js' ), array( 'jquery' ), filemtime( get_theme_file_path( '/assets/js/common.min.js' ) ), true );

		// Комментарии.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Отключаем глобальные стили для гутенберга, если пользователь не залогинен.
		if ( ! is_user_logged_in() ) {
			wp_dequeue_style( 'global-styles' );
		}

		// Magnific.
		wp_register_style( 'magnific-styles', get_theme_file_uri( '/assets/libs/magnific-popup/magnific-popup.min.css' ), array(), filemtime( get_theme_file_path( '/assets/libs/magnific-popup/magnific-popup.min.css' ) ) );
		wp_register_script( 'magnific-scripts', get_theme_file_uri( '/assets/libs/magnific-popup/jquery.magnific-popup.min.js' ), array( 'jquery' ), filemtime( get_theme_file_path( '/assets/libs/magnific-popup/jquery.magnific-popup.min.js' ) ), true );

		// Swiper.
		wp_register_style( 'swiper-styles', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css' );
		wp_register_script( 'swiper-scripts', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', array( 'jquery' ) );

		// Slick.
		wp_register_style( 'slick-styles', get_theme_file_uri( '/assets/css/slick.min.css' ), array(), filemtime( get_theme_file_path( '/assets/css/slick.min.css' ) ) );
		wp_register_script( 'slick-scripts', get_theme_file_uri( '/assets/libs/slick/slick.min.js' ), array( 'jquery' ), filemtime( get_theme_file_path( '/assets/libs/slick/slick.min.js' ) ), true );

		// Register form scripts
		wp_register_script( 'handler-form', get_theme_file_uri( '/assets/js/handler-form.min.js' ), array( 'jquery' ), filemtime( get_theme_file_path( '/assets/js/handler-form.min.js' ) ), true );
		wp_localize_script( 
			'handler-form',
			'form_obj',
			array(
				'url'   => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'form-nonce' ),
			)
		);

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

add_action( 'widgets_init', 'wpgen_widgets_init' );
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