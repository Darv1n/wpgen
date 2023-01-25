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

		<header class="content-area-header" aria-label="<?php echo _x( '404 page header', 'aria-label', 'wpgen' ); ?>">
			<h1 class="content-area-title"><?php _e( 'Oops! That page can&rsquo;t be found', 'wpgen' ); ?></h1>
		</header>

		<section class="content-area-content" aria-label="<?php echo _x( '404 page content', 'aria-label', 'wpgen' ); ?>">
			<p><?php _e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'wpgen' ); ?></p>

			<?php get_search_form(); ?>
			<?php do_action( '404_widgets' ); ?>
		</section>

	</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar( 'right' );
} elseif ( wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar();
}

get_footer();
