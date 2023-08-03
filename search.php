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
		<?php $i = 0; ?>

		<header class="content-area-header" aria-label="<?php _e( 'Search page header', 'wpgen' ); ?>">
			<h1 class="content-area-title">
				<?php printf( __( 'Search Results for: %s', 'wpgen' ), '<span class="search-query">' . get_search_query() . '</span>' ); ?>
			</h1>
		</header>

		<section class="content-area-content" aria-label="<?php _e( 'Search page content', 'wpgen' ); ?>">

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

					<div <?php wpgen_archive_page_columns_classes( $i ); ?>>

						<?php
							// Get a template with a post type, if there is one in the theme.
							if ( file_exists( get_theme_file_path( 'templates/archive/archive-content-type-' . get_post_type() . '.php' ) ) ) {
								get_template_part( 'templates/archive/archive-content-type', get_post_type(), array( 'counter' => $i ) );
							} elseif ( wpgen_options( 'archive_' . get_post_type() . '_template_type' ) ) {
								get_template_part( 'templates/archive/archive-content-type', wpgen_options( 'archive_' . get_post_type() . '_template_type' ), array( 'counter' => $i ) );
							} else {
								get_template_part( 'templates/archive/archive-content-type', 'tils', array( 'counter' => $i ) );
							}
						?>

					</div>

					<?php $i++; ?>
				<?php endwhile; ?>

			</div>
		</section>

		<footer class="content-area-footer" aria-label="<?php _e( 'Search page footer', 'wpgen' ); ?>">
			<?php get_template_part( 'templates/archive/archive', 'pagination' ); ?>
		</footer>

	<?php else : ?>

		<?php get_template_part( 'templates/archive/archive-content', 'none' ); ?>

	<?php endif; ?>

</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar( 'right' );
} elseif ( wpgen_options( 'sidebar_right_display' ) ) {
	get_sidebar();
}

get_footer();
