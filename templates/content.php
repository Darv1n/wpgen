<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */

?>

<article <?php post_class( 'article-single' ); ?>>
	<header class="entry__part entry__header">
		<?php

		if ( is_singular() ) :
			the_title( '<h1 class="entry__title">', '</h1>' );
		else :
			the_title( '<h2 class="entry__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		?>

		<?php if ( get_post_type() === 'post' ) : ?>
			<div class="entry__part entry__meta">
				<?php wpgen_posted_on(); ?>
				<?php wpgen_posted_by(); ?>
			</div><!-- .entry__meta -->
		<?php endif; ?>
	</header><!-- .entry__header -->

	<?php wpgen_post_thumbnail(); ?>

	<div class="entry__part entry__content">
		<?php

		the_content( sprintf( wp_kses( __( 'Continue reading <span class="screen-reader-text">«%s»</span>', 'wpgen' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );

		$link_pages_args = array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpgen' ),
			'after'  => '</div>',
		);

		wp_link_pages( $link_pages_args );

		?>
	</div>

	<footer class="entry__part entry__footer">
		<?php the_wpgen_entry_footer(); ?>
	</footer>
</article>
