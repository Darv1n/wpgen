<?php
/**
 * Template part for displaying archive entry post title.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<div class="post-header" aria-label="<?php _e( 'Post title', 'wpgen' ); ?>">
	<h2 class="post-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
</div>
