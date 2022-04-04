<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */

get_header();

if ( true == wpgen_options( 'sidebar_left_display' ) ) {
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

					<?php
					// Start the Loop
					while ( have_posts() ) :
						the_post();  ?>

							<div <?php wpgen_archive_page_columns_classes(); ?>>

								<?php $post_type = get_post_type();

								// подключаем шаблон с пост тайпом, если есть в теме
								if ( file_exists( get_theme_file_path( 'templates/archive/archive-' . $post_type . '.php' ) ) ) {
									get_template_part( 'templates/archive/archive-' . $post_type );
								} else {
									if ( 'simple' == wpgen_options( 'archive_page_template_type' ) ) {
										get_template_part( 'templates/archive/archive-simple' );
									} elseif ( 'banners' == wpgen_options( 'archive_page_template_type' ) ) {
										get_template_part( 'templates/archive/archive-banners' );
									} elseif ( 'tils' == wpgen_options( 'archive_page_template_type' ) ) {
										get_template_part( 'templates/archive/archive-tils' );
									} else {
										get_template_part( 'templates/archive/archive-list' );
									} 
								} ?>

							</div>
						
					<?php endwhile; ?>

				</div>

			<?php do_action( 'wpgen_after_archive_page_content' ); ?>

			<?php the_wpgen_posts_navigation();
				//the_posts_navigation();

		else :

			get_template_part( 'templates/content', 'none' );

		endif; ?>

		<?php do_action( 'wpgen_after_archive_page' );  ?>

	</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) === true && wpgen_options( 'sidebar_right_display' ) === true ) {
	get_sidebar( 'right' );
} elseif( wpgen_options( 'sidebar_right_display' ) === true ) {
	get_sidebar();
}

get_footer();
