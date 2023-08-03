<?php
/**
 * Template part for displaying similar posts in single.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<?php if ( get_post_type() === 'post' && wpgen_options( 'single_' . get_post_type() . '_similar_posts_display' ) ) { ?>

	<section id="similar-posts" class="section section_similar-posts similar-posts" aria-label="<?php _e( 'Similar posts', 'wpgen' ); ?>">

		<h2 class="section-title"><?php printf( __( 'Similar %1$s', 'wpgen' ), '123' ); ?></h2>

		<?php

			$args = array(
				'post__not_in'   => array( get_the_ID() ),
				'order'          => wpgen_options( 'single_' . get_post_type() . '_similar_posts_order' ),
				'orderby'        => wpgen_options( 'single_' . get_post_type() . '_similar_posts_orderby' ),
				'posts_per_page' => wpgen_options( 'single_' . get_post_type() . '_similar_posts_count' ),
			);

			if ( get_post_type() === 'post' && has_category() ) {
				foreach ( get_the_category() as $key => $cat ) {
					$args['category__in'][] = $cat->term_id;
				}
			}

			if ( get_post_type() === 'post' && has_tag() ) {
				foreach ( get_the_tags() as $key => $tag ) {
					$args['tag__in'][] = $tag->term_id;
				}
			}

			$query = new wp_query( $args );

			if ( $query->have_posts() ) : ?>
				<?php $i = 0; ?>

				<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>

				<?php while ( $query->have_posts() ) : ?>
					<?php $query->the_post(); ?>

					<div <?php wpgen_archive_page_columns_classes( $i ); ?>>

						<?php
							// Get a template with a post type, if there is one in the theme.
							if ( file_exists( get_theme_file_path( 'templates/archive/archive-content-type-' . get_post_type() . '.php' ) ) ) {
								get_template_part( 'templates/archive/archive-content-type', get_post_type(), array( 'counter' => $i ) );
							} elseif ( wpgen_options( 'archive_' . get_post_type() . '_template_type' ) ) {
								get_template_part( 'templates/archive/archive-content-type', wpgen_options( 'archive_' . get_post_type() . '_template_type' ), array( 'counter' => $i ) );
							} else {
								get_template_part( 'templates/archive/archive-content-type', 'tils', array( 'counter' => $i ) );
							}
						?>

					</div>

					<?php $i++; ?>
				<?php endwhile; ?>

				</div>

			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

	</section>

<?php } ?>
