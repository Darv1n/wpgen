<?php
/**
 * Template tils for displaying posts
 *
 * @package wpgen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		if ( has_post_thumbnail() ) {
			$background_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		} else {
			$background_image = get_stylesheet_directory_uri() . '/assets/img/default-banner.jpg';
		}
	?>

	<a class="post-thumbnail" href="<?php the_permalink(); ?>" style="background: url( <?php echo esc_url( $background_image ); ?> ) center/cover no-repeat" aria-hidden="true" tabindex="-1" role="img"></a>

	<?php if ( wpgen_options( 'archive_' . get_post_type() . '_meta_display' ) ) { ?>
		<ul class="post-meta">
			<?php the_wpgen_archive_meta_list(); ?>
		</ul>
	<?php } ?>

	<div class="post-header">
		<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	</div>

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
