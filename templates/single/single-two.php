<?php
/**
 * Single post template TWO
 *
 * @package wpgen
 */

?>

<article <?php post_class( 'article-single' ); ?>>

	<header class="entry__part entry__header article-header" role="banner" aria-label="<?php esc_html_e( 'Header of the article with title', 'wpgen' ); ?>">
		<?php the_title( '<h1 class="entry__title">', '</h1>' ); ?>
	</header>

	<div class="row">

		<div class="col-12 col-sm-12 col-lg-4 col-xl-3 d-flex align-content-between flex-wrap">

			<?php do_action( 'before_meta_list' ); ?>

				<div <?php wpgen_meta_display_classes(); ?>>
					<?php the_wpgen_post_meta_list( '<ul class="meta">', '</ul>'); ?>
				</div>

			<?php do_action( 'after_meta_list' ); ?>

		</div>
		<div class="col-12 col-sm-12 col-lg-8 col-xl-9">

			<?php if ( has_post_thumbnail() && wpgen_options( 'single_post_thumbnail_display' ) ) { ?>
				<div class="entry__part entry__thumbnail post-thumbnail">
					<?php the_post_thumbnail(); ?>
				</div>
			<?php }	?>

			<div class="entry__part entry__content">
				<?php

					do_action( 'wpgen_before_single_post_content' );

					the_content( sprintf( wp_kses( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'wpgen' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );

					do_action( 'wpgen_after_single_post_content' );

				?>
			</div>

			<?php if ( wpgen_options( 'single_post_entry_footer_display' ) ) { ?>
				<footer class="entry__part entry__footer article-footer" role="contentinfo" aria-label="<?php esc_html_e( 'Footer of the article with additional information', 'wpgen' ); ?>">
					<?php the_wpgen_entry_footer(); ?>
				</footer>
			<?php } ?>

		</div>

	</div>

</article>
