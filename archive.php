<?php
/**
 * The template for displaying archive pages
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

	<?php do_action( 'wpgen_before_archive_page' ); ?>

	<?php if ( have_posts() ) : ?>
		<?php $i = 0; ?>

		<header class="content-area-header" aria-label="<?php echo _x( 'Archive page header', 'aria-label', 'wpgen' ); ?>">
			<?php the_archive_title( '<h1 class="content-area-title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="content-area-description">', '</div>' ); ?>
		</header>

		<?php do_action( 'wpgen_before_archive_page_content' ); ?>

		<section class="content-area-content" aria-label="<?php echo _x( 'Archive page content', 'aria-label', 'wpgen' ); ?>">
			<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>

				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>

					<div <?php wpgen_archive_page_columns_classes( $i ); ?>>

						<?php
							// Get a template with a post type, if there is one in the theme.
							if ( file_exists( get_theme_file_path( 'templates/archive/archive-' . get_post_type() . '.php' ) ) ) {
								get_template_part( 'templates/archive/archive', get_post_type(), array( 'counter' => $i ) );
							} else {
								get_template_part( 'templates/archive/archive', wpgen_options( 'archive_' . get_post_type() . '_template_type' ), array( 'counter' => $i ) );
							}
						?>

					</div>

					<?php $i++; ?>
				<?php endwhile; ?>

			</div>
		</section>

		<?php do_action( 'wpgen_after_archive_page_content' ); ?>

		<footer class="content-area-footer" aria-label="<?php echo _x( 'Archive page footer', 'aria-label', 'wpgen' ); ?>">
			<?php the_wpgen_posts_navigation(); ?>
		</footer>

	<?php else : ?>

		<?php get_template_part( 'templates/content', 'none' ); ?>

	<?php endif; ?>

	<?php do_action( 'wpgen_after_archive_page' ); ?>

</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar( 'right' );
} elseif ( wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar();
}

get_footer();
