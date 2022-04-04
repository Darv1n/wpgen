<?php
/**
 * Template banners for displaying posts
 *
 * @package wpgen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( wpgen_options( 'archive_page_meta_display' ) === true ) { ?>
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

	<a class="entry__part entry__thumbnail post-thumbnail post-thumbnail-bg" href="<?php the_permalink(); ?>" style="background: url( <?php echo $background_image ?> ) center no-repeat" aria-hidden="true" tabindex="-1">

		<?php 
		//	This code displays the thumbnail of the post. It is replaced by background-image due to the convenience of resizing.
		//	Этот код выводит thumbnail поста. Он заменен на background-image из-за удобства ресайза
		/*	if ( has_post_thumbnail() ) {
			$attr = apply_filters( 'wpgen_singular_thumbnail_attr', array('alt'=>get_the_title(), 'title'=>get_the_title()) );
			the_post_thumbnail( 'large', $attr );
		} else {
			$default_image = get_template_directory_uri() . '/assets/img/default/default-banner-blue.jpg';
			echo '<img class="wp-post-image" src="' . $default_image . '" alt="' . get_the_title() . '" title="' . get_the_title() . '">';
		}*/ ?>
		
	</a>

	<div class="entry__part entry__header">
		<h2 class="entry__title"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
	</div>

</article>
