<?php
/**
 * Template part for displaying entry content.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<div class="post-content" aria-label="<?php _e( 'Post content', 'wpgen' ); ?>">
	<?php the_content( sprintf( wp_kses( __( 'Continue reading <span class="screen-reader-text">"%s"</span>', 'wpgen' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) ); ?>
</div>
