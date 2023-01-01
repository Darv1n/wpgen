<?php
/**
 * Template banners for displaying posts
 *
 * @package wpgen
 */

?>

<article <?php post_class(); ?>>

	<?php if ( wpgen_options( 'archive_page_meta_display' ) ) { ?>
		<div class="entry__part entry__meta entry__meta_inline">
			<?php the_wpgen_archive_meta_list( '<ul class="meta">', '</ul>' ); ?>
		</div>
	<?php } ?>

	<?php

		if ( has_post_thumbnail() ) {
			$background_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		} else {
			$background_image = get_stylesheet_directory_uri() . '/assets/img/default-banner.jpg';
		}

	?>

	<a class="entry__part entry__thumbnail post-thumbnail post-thumbnail-bg" href="<?php the_permalink(); ?>" style="background: url( <?php echo esc_url( $background_image ); ?> ) center/cover no-repeat" aria-hidden="true" tabindex="-1" role="img"></a>

	<div class="entry__part entry__header">
		<h2 class="entry__title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	</div>

</article>
