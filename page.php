<?php
/**
 * The template for disdisdisplaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
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

		<?php do_action( 'wpgen_before_single_page' ); ?>

		<?php while ( have_posts() ) :
			the_post();

			do_action( 'wpgen_before_article_page' );

			get_template_part( 'templates/content', 'page' );

			do_action( 'wpgen_after_article_page' );

			//	@hooked the_wpgen_post_navigation 15
			do_action( 'wpgen_before_comment_form' ); 

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			do_action( 'wpgen_after_comment_form' );

		endwhile; ?>

		<?php do_action( 'wpgen_after_single_page' ); ?>

	</main>

<?php

if ( wpgen_options( 'sidebar_left_display' ) === true && wpgen_options( 'sidebar_right_display' ) === true ) {
	get_sidebar( 'right' );
} elseif( wpgen_options( 'sidebar_right_display' ) === true ) {
	get_sidebar();
}

get_footer();
