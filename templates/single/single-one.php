<?php
/**
 * Single post template ONE
 *
 * @package wpgen
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post_single' ); ?>>

	<header class="post-header" aria-label="<?php echo _x( 'Post header', 'aria-label', 'wpgen' ); ?>">
		<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>

		<?php if ( wpgen_options( 'single_' . get_post_type() . '_meta_display' ) ) { ?>
			<ul class="post-meta" aria-label="<?php echo _x( 'Post meta information', 'aria-label', 'wpgen' ); ?>">
				<?php the_wpgen_post_meta_list(); ?>
			</ul>
		<?php } ?>
	</header>

	<?php if ( has_post_thumbnail() && wpgen_options( 'single_' . get_post_type() . '_thumbnail_display' ) ) { ?>
		<div class="post-thumbnail" aria-label="<?php echo _x( 'Post thumbnail', 'aria-label', 'wpgen' ); ?>">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php } ?>

	<div class="post-content" aria-label="<?php echo _x( 'Post content', 'aria-label', 'wpgen' ); ?>">
		<?php
			do_action( 'wpgen_before_single_post_content' );

			the_content( sprintf( wp_kses( __( 'Continue reading <span class="screen-reader-text">"%s"</span>', 'wpgen' ), array( 'span' => array( 'class' => array() ) ) ), get_the_title() ) );

			do_action( 'wpgen_after_single_post_content' );
		?>
	</div>

	<?php if ( wpgen_options( 'single_' . get_post_type() . '_entry_footer_display' ) ) { ?>
		<footer class="post-footer" aria-label="<?php echo _x( 'Post footer', 'aria-label', 'wpgen' ); ?>">
			<?php the_wpgen_entry_footer(); ?>
		</footer>
	<?php } ?>

</article>
