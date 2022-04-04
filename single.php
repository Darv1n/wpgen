<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package wpgen
 */

get_header();

if ( true == wpgen_options( 'sidebar_left_display' ) ) {
	get_sidebar();
} ?>

	<main id="primary" <?php wpgen_content_area_classes(); ?> role="main">

		<?php do_action( 'wpgen_before_single_post' ); ?>

		<?php while ( have_posts() ) :
			the_post();

			$post_type = get_post_type();

			do_action( 'wpgen_before_article_post' );

			// подключаем шаблон с пост тайпом, если есть в теме
			if ( file_exists( get_theme_file_path( 'templates/single/single-' . $post_type . '.php' ) ) ) {
				get_template_part( 'templates/single/single-' . $post_type );
			} else {
				if ( 'two' == wpgen_options( 'single_post_template_type' ) ) {
					get_template_part( 'templates/single/single', 'two' );
				} else {
					get_template_part( 'templates/single/single', 'one' );
				}
			}

			do_action( 'wpgen_after_article_post' );

			//	@hooked the_wpgen_post_navigation 15
			do_action( 'wpgen_before_comment_form' ); 

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			//	@hooked the_wpgen_similar_posts 15
			do_action( 'wpgen_after_comment_form' ); 

		endwhile; ?>

		<?php do_action( 'wpgen_after_single_post' ); ?>

	</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) === true && wpgen_options( 'sidebar_right_display' ) === true ) {
	get_sidebar( 'right' );
} elseif( wpgen_options( 'sidebar_right_display' ) === true ) {
	get_sidebar();
}

get_footer();
