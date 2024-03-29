<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package wpgen
 */

get_header();

if ( wpgen_options( 'sidebar_left_display' ) ) {
	get_sidebar();
} ?>

	<main id="primary" <?php wpgen_content_area_classes(); ?> role="main">

		<?php do_action( 'wpgen_before_single_post' ); ?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<?php do_action( 'wpgen_before_single_content_part' ); ?>

				<?php
					// Get a template with a post type, if there is one in the theme.
					if ( file_exists( get_theme_file_path( 'templates/single/single-content-type-' . get_post_type() . '.php' ) ) ) {
						get_template_part( 'templates/single/single-content-type', get_post_type() );
					} else {
						get_template_part( 'templates/single/single-content-type', wpgen_options( 'single_' . get_post_type() . '_template_type' ) );
					}
				?>

			<?php do_action( 'wpgen_after_single_content_part' ); ?>

			<?php if ( wpgen_options( 'single_' . get_post_type() . '_post_nav_display' ) && get_previous_post_link() && get_next_post_link() ) {?>
				<?php get_template_part( 'templates/single', 'pagination' ); ?>
			<?php } ?>

			<?php do_action( 'wpgen_before_comment_form' ); ?>

				<!-- If comments are open or we have at least one comment, load up the comment template -->
				<?php if ( comments_open() || get_comments_number() ) : ?>
					<?php comments_template(); ?>
				<?php endif; ?>

			<?php do_action( 'wpgen_after_comment_form' ); ?>

			<?php get_template_part( 'templates/single', 'similar-posts' ); ?>

		<?php endwhile; ?>

		<?php do_action( 'wpgen_after_single_post' ); ?>

	</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar( 'right' );
} elseif ( wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar();
}

get_footer();
