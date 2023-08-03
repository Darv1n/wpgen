<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<header class="content-area-header">
	<h2 class="content-area-title"><?php _e( 'Nothing Found', 'wpgen' ); ?></h2>
</header>

<section class="content-area-content no-results">

	<div class="no-results-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) { ?>
			<?php printf( '<p>' . wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>', 'wpgen' ), array( 'a' => array( 'href' => array() ) ) ) . '</p>', esc_url( admin_url( 'post-new.php' ) ) ); ?>
		<?php } elseif ( is_search() ) { ?>
			<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords', 'wpgen' ); ?></p>
		<?php } else { ?>
			<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help', 'wpgen' ); ?></p>
		<?php } ?>
	</div>

</section>

<footer class="content-area-footer">
	<?php get_search_form(); ?>
</footer>
