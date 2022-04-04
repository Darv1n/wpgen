<?php
/**
 * Theme header
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wpgen
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wpgen' ); ?></a>

	<header id="masthead" <?php wpgen_header_classes(); ?> role="banner" aria-label="<?php esc_html_e( 'Main Site Header', 'wpgen' ); ?>">

		<?php do_action( 'wp_header_open' ); ?>

		<?php if ( wpgen_options( 'general_top_bar_display' ) === true ) { ?>
		
			<!-- Header Top Bar -->
			<div id="header__top-bar" class="header__top-bar">
				<div <?php wpgen_container_classes(); ?>>
					<div class="row align-items-center">
						<div class="header__item text-md-left col-12 col-xs-12 col-md-6"><?php dynamic_sidebar( 'sidebar-top-left' ); ?></div>
						<div class="header__item text-md-right col-12 col-xs-12 col-md-6"><?php dynamic_sidebar( 'sidebar-top-right' ); ?></div>
					</div>
				</div>
			</div>

		<?php } ?>
	
		<?php 

			if( wpgen_options( 'general_header_type' ) === 'header-content' ) {
				get_template_part( 'templates/header/header-content');
			} elseif( wpgen_options( 'general_header_type' ) === 'header-logo-center' ) {
				get_template_part( 'templates/header/header-logo-center');
			} else {
				get_template_part( 'templates/header/header-simple');
			}

		?>

		<?php do_action( 'wp_header_close' ); ?>

	</header>

	<?php

	// hooked wpgen_breadcrumbs - 10
	do_action( 'before_site_content' ); ?>

	<div id="content" class="site__content">

		<div <?php wpgen_container_classes(); ?>>
			<div class="row">