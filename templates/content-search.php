<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */

?>

<article <?php post_class( 'article-single article-search' ); ?>>
	<header class="entry__part entry__header">
		<?php the_title( sprintf( '<h2 class="entry__title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( get_post_type() === 'post' ) { ?>
			<div class="entry__part entry__meta">
				<?php wpgen_posted_on(); ?>
				<?php wpgen_posted_by(); ?>
			</div>
		<?php } ?>
	</header>
	<?php wpgen_post_thumbnail(); ?>

	<div class="entry__part entry__excerpt">
		<?php the_excerpt(); ?>
	</div>

	<footer class="entry__part entry__footer">
		<?php the_wpgen_entry_footer(); ?>
	</footer>
</article>
