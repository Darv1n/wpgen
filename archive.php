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

		<header class="entry__header" role="banner" aria-label="<?php esc_html_e( 'Archive Page Header', 'wpgen' ); ?>">
			<?php
				the_archive_title( '<h1 class="archive-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
			?>
		</header>

		<?php if ( have_posts() ) : ?>

			<?php do_action( 'wpgen_before_archive_page_content' ); ?>

				<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>

					<!-- Start the Loop -->
					<?php while ( have_posts() ) : ?>
						<?php the_post(); ?>

							<div <?php wpgen_archive_page_columns_classes(); ?>>

								<?php

									// Get a template with a post type, if there is one in the theme.
									if ( file_exists( get_theme_file_path( 'templates/archive/archive-' . get_post_type() . '.php' ) ) ) {
										get_template_part( 'templates/archive/archive-' . get_post_type() );
									} else {
										if ( wpgen_options( 'archive_page_template_type' ) === 'simple' ) {
											get_template_part( 'templates/archive/archive-simple' );
										} elseif ( wpgen_options( 'archive_page_template_type' ) === 'banners' ) {
											get_template_part( 'templates/archive/archive-banners' );
										} elseif ( wpgen_options( 'archive_page_template_type' ) === 'tils' ) {
											get_template_part( 'templates/archive/archive-tils' );
										} else {
											get_template_part( 'templates/archive/archive-list' );
										}
									}

								?>

							</div>

					<?php endwhile; ?>

				</div>

			<?php do_action( 'wpgen_after_archive_page_content' ); ?>

			<?php the_wpgen_posts_navigation(); ?>

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
