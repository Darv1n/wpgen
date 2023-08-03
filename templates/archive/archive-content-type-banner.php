<?php
/**
 * Template banners for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php

		// Post title part.
		get_template_part( 'templates/archive/archive-entry', 'post-title' ); 

		// Post thumbnail part.
		get_template_part( 'templates/archive/archive-entry', 'post-thumbnail' );

		// Post meta part.
		if ( wpgen_options( 'archive_' . get_post_type() . '_meta_display' ) ) {
			get_template_part( 'templates/archive/archive-entry', 'post-meta' );
		}

	?>

</article>
