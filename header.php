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
	<link href="https://gmpg.org/xfn/11" rel="profile">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<?php wp_body_open(); ?>

	<header id="header" <?php wpgen_header_classes(); ?> aria-label="<?php echo _x( 'Site header', 'aria-label', 'wpgen' ); ?>">

		<?php do_action( 'wp_header_open' ); ?>

		<?php if ( wpgen_options( 'general_header_top_bar_display' ) ) { ?>
			<div id="header__top-bar" class="header__top-bar">
				<div <?php wpgen_container_classes( 'container-header' ); ?>>

					<?php get_template_part( 'templates/header/header-top-bar' ); ?>

				</div>
			</div>
		<?php } ?>

		<?php
			if ( wpgen_options( 'general_header_type' ) === 'header-content' ) {
				get_template_part( 'templates/header/header-content' );
			} elseif ( wpgen_options( 'general_header_type' ) === 'header-logo-center' ) {
				get_template_part( 'templates/header/header-logo-center' );
			} else {
				get_template_part( 'templates/header/header-simple' );
			}
		?>

		<?php do_action( 'wp_header_close' ); ?>

	</header>

	<?php 
		/**
		 * Hook: before_site_content.
		 *
		 * @hooked wpgen_breadcrumbs                   - 10
		 * @hooked wpgen_first_screen (if need)        - 30
		 * @hooked wpgen_section_content_wrapper_start - 50
		 */
		do_action( 'before_site_content' );
