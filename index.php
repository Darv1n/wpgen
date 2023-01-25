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
				<header class="content-area-header" aria-label="<?php echo _x( 'Archive page header', 'aria-label', 'wpgen' ); ?>">
					<h1 class="content-area-title"><?php single_post_title(); ?></h1>
				</header>
			<?php } ?>

			<section class="content-area-content" aria-label="<?php echo _x( 'Archive page content', 'aria-label', 'wpgen' ); ?>">
				<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>

					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>

						<div <?php wpgen_archive_page_columns_classes(); ?>>

							<?php
								// Get a template with a post type, if there is one in the theme.
								if ( file_exists( get_theme_file_path( 'templates/archive/archive-' . get_post_type() . '.php' ) ) ) {
									get_template_part( 'templates/archive/archive-' . get_post_type() );
								} else {
									get_template_part( 'templates/archive/archive', wpgen_options( 'archive_' . get_post_type() . '_template_type' ) );
								}
							?>

						</div>

					<?php endwhile; ?>

				</div>
			</section>

			<footer class="content-area-footer" aria-label="<?php echo _x( 'Archive page footer', 'aria-label', 'wpgen' ); ?>">
				<?php the_wpgen_posts_navigation(); ?>
			</footer>

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
