<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to disdisplay a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */

get_header();

if ( wpgen_options( 'sidebar_left_display' ) ) {
	get_sidebar();
} ?>

	<main id="primary" <?php wpgen_content_area_classes(); ?> role="main">

		<?php do_action( 'wpgen_before_index_page' ); ?>

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) { ?>
				<header>
					<h1 class="entry__title screen-reader-text"><?php single_post_title(); ?></h1>
				</header>
			<?php } ?>

			<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>

				<!-- Start the Loop -->
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>

					<div <?php wpgen_archive_page_columns_classes(); ?>>

						<?php get_template_part( 'templates/archive/archive', wpgen_options( 'archive_page_template_type' ) ); ?>

					</div>

				<?php endwhile; ?>

			</div>

			<?php the_wpgen_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'templates/content', 'none' ); ?>

		<?php endif; ?>

		<?php do_action( 'wpgen_after_index_page' ); ?>

	</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar( 'right' );
} elseif ( wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar();
}

get_footer();
