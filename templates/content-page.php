<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */

?>

<article <?php post_class( 'article-single article-page' ); ?>>
	<header class="entry__part entry__header">
		<?php the_title( '<h1 class="entry__title">', '</h1>' ); ?>
	</header>

	<?php if ( has_post_thumbnail() ) { ?>
		<div class="entry__part post-thumbnail entry__thumbnail">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php } ?>

	<div class="entry__part entry__content">

		<?php

			do_action( 'wpgen_before_single_page_content' );

			the_content();

			$link_pages_args = array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpgen' ),
				'after'  => '</div>',
			);

			wp_link_pages( $link_pages_args );

			do_action( 'wpgen_after_single_page_content' );

			?>
	</div>

	<?php if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) { ?>
		<footer class="entry__part entry__footer">
			<a <?php link_classes( 'edit-link' ); ?> href="<?php echo esc_url( get_edit_post_link() ); ?>"><?php esc_html_e( 'Edit', 'wpgen' ); ?></a>
		</footer>
	<?php } ?>
</article>
