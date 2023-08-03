<?php
/**
 * Template part for displaying entry header.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<header class="post-header" aria-label="<?php _e( 'Post header', 'wpgen' ); ?>">
	<?php do_action( 'wpgen_before_single_inner_entry_header' ); ?>

	<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>

	<?php do_action( 'wpgen_after_single_inner_entry_header' ); ?>
</header>
