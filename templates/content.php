<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('article-single'); ?>>
	<header class="entry__part entry__header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry__title">', '</h1>' );
		else :
			the_title( '<h2 class="entry__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry__part entry__meta">
				<?php
				wpgen_posted_on();
				wpgen_posted_by();
				?>
			</div><!-- .entry__meta -->
		<?php endif; ?>
	</header><!-- .entry__header -->

	<?php wpgen_post_thumbnail(); ?>

	<div class="entry__part entry__content">
		<?php
		the_content( sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'wpgen' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		) );

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpgen' ),
			'after'  => '</div>',
		) );
		?>
	</div>

	<footer class="entry__part entry__footer">
		<?php the_wpgen_entry_footer(); ?>
	</footer>
</article>
