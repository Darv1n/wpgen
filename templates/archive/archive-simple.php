<?php
/**
 * Template simple for displaying posts
 *
 * @package wpgen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry__part entry__header">
		<h2 class="entry__title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	</div>

	<?php if ( wpgen_options( 'archive_page_meta_display' ) === true ) { ?>
		<div class="entry__part entry__meta entry__meta_inline">
			<?php the_wpgen_archive_meta_list( '<ul class="meta">', '</ul>' ); ?>
		</div>
	<?php } ?>

	<?php if ( wpgen_options( 'archive_page_detail_description' ) !== 'nothing' ) { ?>
		<?php if ( wpgen_options( 'archive_page_detail_description' ) === 'content' ) { ?>
			<div class="entry__part entry__content">
				<?php the_content(); ?>
			</div>
		<?php } else { ?>
			<div class="entry__part entry__excerpt">
				<?php the_excerpt(); ?>
			</div>
		<?php } ?>
	<?php } ?>

	<?php if ( wpgen_options( 'archive_page_detail_button' ) !== 'nothing' ) { ?>
		<div class="entry__part entry__link-more">
			<a <?php echo wpgen_link_more_classes(); ?> href="<?php the_permalink() ?>"><?php _e( 'Read more', 'wpgen' ); ?></a>
		</div>
	<?php } ?>

</article>
