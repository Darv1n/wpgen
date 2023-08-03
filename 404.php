<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package wpgen
 */

get_header();

if ( wpgen_options( 'sidebar_left_display' ) ) {
	get_sidebar();
} ?>

<main id="primary" <?php wpgen_content_area_classes(); ?> role="main">

	<?php do_action( 'wpgen_before_404_page' ); ?>

	<header class="content-area-header" aria-label="<?php _e( '404 page header', 'wpgen' ); ?>">
		<h1 class="content-area-title"><?php _e( 'Oops! That page can&rsquo;t be found', 'wpgen' ); ?></h1>
	</header>

	<section class="content-area-content" aria-label="<?php _e( '404 page content', 'wpgen' ); ?>">
		<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'wpgen' ); ?></p>

		<?php get_search_form(); ?>
	</section>

	<footer class="content-area-footer" aria-label="<?php _e( '404 page footer', 'wpgen' ); ?>">
		<?php do_action( '404_widgets' ); ?>
	</footer>

	<?php do_action( 'wpgen_after_404_page' ); ?>

</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar( 'right' );
} elseif ( wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar();
}

get_footer();
