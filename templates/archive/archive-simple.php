<?php
/**
 * Template simple for displaying posts
 *
 * @package wpgen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="post-header">
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	</div>

	<?php if ( wpgen_options( 'archive_' . get_post_type() . '_meta_display' ) ) { ?>
		<div class="post-meta">
			<?php the_wpgen_archive_meta_list(); ?>
		</div>
	<?php } ?>

	<?php if ( wpgen_options( 'archive_' . get_post_type() . '_detail_description' ) !== 'nothing' ) { ?>
		<?php if ( wpgen_options( 'archive_' . get_post_type() . '_detail_description' ) === 'content' ) { ?>
			<div class="post-content">
				<?php the_content(); ?>
			</div>
		<?php } else { ?>
			<div class="post-excerpt">
				<?php the_excerpt(); ?>
			</div>
		<?php } ?>
	<?php } ?>

	<?php if ( wpgen_options( 'archive_' . get_post_type() . '_detail_button' ) !== 'nothing' ) { ?>
		<div class="post-link-more">
			<a <?php wpgen_link_more_classes(); ?> href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'wpgen' ); ?></a>
		</div>
	<?php } ?>

</article>
