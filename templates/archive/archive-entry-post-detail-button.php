<?php
/**
 * Template part for displaying archive entry post detail button.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<div class="post-link-more">
	<a <?php wpgen_link_more_classes(); ?> href="<?php the_permalink(); ?>"><?php _e( 'Read more', 'wpgen' ); ?></a>
</div>
