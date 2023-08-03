<?php
/**
 * Template list for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="row align-items-center">
		<div class="col-12 col-xs-12 col-md-5">

			<?php
				// Post thumbnail part.
				get_template_part( 'templates/archive/archive-entry', 'post-thumbnail' );
			?>

		</div>
		<div class="col-12 col-xs-12 col-md-7">

			<?php

				// Post meta part.
				if ( wpgen_options( 'archive_' . get_post_type() . '_meta_display' ) ) {
					get_template_part( 'templates/archive/archive-entry', 'post-meta' );
				}

				// Post title part.
				get_template_part( 'templates/archive/archive-entry', 'post-title' ); 

				// Post content part.
				if ( wpgen_options( 'archive_' . get_post_type() . '_detail_description' ) !== 'nothing' ) {
					get_template_part( 'templates/archive/archive-entry', 'post-content' );
				}

				// Post footer part.
				if ( wpgen_options( 'archive_' . get_post_type() . '_detail_button' ) !== 'nothing' ) {
					get_template_part( 'templates/archive/archive-entry', 'post-detail-button' );
				}

			?>

		</div>
	</div>

</article>
