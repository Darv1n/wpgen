<?php
/**
 * Template part for displaying post content in single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_single' ); ?>>

<?php

	// Post header part.
	get_template_part( 'templates/single/single-entry', 'post-header' ); 

	// Post thumbnail part.
	if ( has_post_thumbnail() && wpgen_options( 'single_' . get_post_type() . '_thumbnail_display' ) ) {
		get_template_part( 'templates/single/single-entry', 'post-thumbnail' );
	}

	// Post meta part.
	if ( wpgen_options( 'single_' . get_post_type() . '_meta_display' ) ) {
		get_template_part( 'templates/single/single-entry', 'post-meta' );
	}

	// Post content part.
	get_template_part( 'templates/single/single-entry', 'post-content' );

	// Post footer part.
	if ( wpgen_options( 'single_' . get_post_type() . '_entry_footer_display' ) ) {
		get_template_part( 'templates/single/single-entry', 'post-footer' );
	}

?>

</article>
