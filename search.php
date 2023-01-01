<?php
/**
 * The template for disdisplaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package wpgen
 */

get_header();

if ( wpgen_options( 'sidebar_left_display' ) ) {
	get_sidebar();
} ?>

	<main id="primary" <?php wpgen_content_area_classes(); ?> role="main">

		<?php if ( have_posts() ) : ?>

			<header class="entry__header">
				<h1 class="entry__title">
					<?php printf( __( 'Search Results for: %s', 'wpgen' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h1>
			</header>

			<!-- Start the Loop -->
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>

				<?php get_template_part( 'templates/archive/archive-simple' ); ?>

			<?php endwhile; ?>

			<?php the_wpgen_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'templates/content', 'none' ); ?>

		<?php endif; ?>

	</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar( 'right' );
} elseif ( wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar();
}

get_footer();
