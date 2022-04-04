<?php
/**
 * Template sidebar-list for displaying posts
 *
 * @package wpgen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('sidebar-list'); ?>>

	<div class="row no-gutters">
		<div class="col-12 col-xs-12 col-sm-4">

			<?php if ( has_post_thumbnail() ) {
				$background_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
			} else {
				$background_image = get_stylesheet_directory_uri() . '/assets/img/default-banner.jpg';
			} ?>

			<a class="entry__part entry__thumbnail post-thumbnail post-thumbnail-bg" href="<?php the_permalink(); ?>" style="background: url( <?php echo $background_image ?> ) center no-repeat" aria-hidden="true" tabindex="-1"></a>

		</div>
		<div class="col-12 col-xs-12 col-sm-8">

			<div class="archive-post-content">
				
				<?php if ( wpgen_options( 'archive_page_meta_display' ) === true ) { ?>
					<div class="entry__part entry__meta entry__meta_inline">
						<?php the_wpgen_archive_meta_list( '<ul class="meta">', '</ul>' ); ?>
					</div>
				<?php } ?>

				<div class="entry__part entry__header">
					<h2 class="entry__title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				</div>

			</div>

		</div>

	</div>

</article>
