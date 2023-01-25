<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */

?>

<article <?php post_class( 'post_single post_page' ); ?>>

	<header class="post-header">
		<?php the_title( '<h1 class="post-title">', '</h1>' ); ?>
	</header>

	<?php if ( has_post_thumbnail() ) { ?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php } ?>

	<div class="post-content">
		<?php
			do_action( 'wpgen_before_single_page_content' );

			the_content();

			wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wpgen' ), 'after'  => '</div>', ) );

			do_action( 'wpgen_after_single_page_content' );
		?>
	</div>

	<?php if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) { ?>
		<footer class="post-footer">
			<a <?php link_classes( 'edit-link' ); ?> href="<?php echo esc_url( get_edit_post_link() ); ?>"><?php esc_html_e( 'Edit', 'wpgen' ); ?></a>
		</footer>
	<?php } ?>
</article>
