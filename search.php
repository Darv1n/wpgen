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

		<header class="content-area-header" aria-label="<?php echo _x( 'Search page header', 'aria-label', 'wpgen' ); ?>">
			<h1 class="content-area-title">
				<?php printf( __( 'Search Results for: %s', 'wpgen' ), '<span>' . get_search_query() . '</span>' ); ?>
			</h1>
		</header>

		<section class="content-area-content" aria-label="<?php echo _x( 'Search page content', 'aria-label', 'wpgen' ); ?>">

			<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>

				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>

					<?php $post_type_object = get_post_type_object( get_post_type() ); ?>

					<?php if ( ! isset( $post_type_current ) ) : ?>
						<div <?php wpgen_archive_page_columns_classes( '', 1 ); ?>>
							<h2 class="post-type-title h4"><?php _e( 'Post type:' ) ?> <?php echo esc_html( $post_type_object->name ); ?></h2>
						</div>
					<?php endif; ?>

					<?php if ( isset( $post_type_current ) && $post_type_current !== get_post_type() ) : ?>
						</div>
						<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>
							<div <?php wpgen_archive_page_columns_classes( '', 1 ); ?>>
								<h2 class="post-type-title h4"><?php _e( 'Post type:' ) ?> <?php echo esc_html( $post_type_object->name ); ?></h2>
							</div>
					<?php endif; ?>

					<?php $post_type_current = get_post_type(); ?>

					<div <?php wpgen_archive_page_columns_classes(); ?>>

						<?php
							// Get a template with a post type, if there is one in the theme.
							if ( file_exists( get_theme_file_path( 'templates/archive/archive-' . get_post_type() . '.php' ) ) ) {
								get_template_part( 'templates/archive/archive-' . get_post_type() );
							} else {
								get_template_part( 'templates/archive/archive', wpgen_options( 'archive_' . $post_type_current . '_template_type' ) );
							}
						?>

					</div>

				<?php endwhile; ?>

			</div>
		</section>

		<footer class="content-area-footer" aria-label="<?php echo _x( 'Search page footer', 'aria-label', 'wpgen' ); ?>">
			<?php the_wpgen_posts_navigation(); ?>
		</footer>

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
