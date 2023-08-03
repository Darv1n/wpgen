<?php
/**
 * Template part for displaying entry footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<footer class="post-footer" aria-label="<?php _e( 'Post footer', 'wpgen' ); ?>">

	<?php wp_link_pages( array(
			'before' => '<div class="post-footer__item post-footer__pages">' . __( 'Pages:', 'wpgen' ),
			'after'  => '</div>',
			'echo'   => 0,
		) ); ?>

	<?php if ( get_post_type() === 'post' && wpgen_options( 'single_' . get_post_type() . '_entry_footer_cats_display' ) && has_category() ) { ?>
		<div class="post-footer__item post-footer__cats">
			<strong><?php _e( 'Post categories', 'wpgen' ) ?></strong>
			<ul class="post-footer__list cat-list">
				<?php foreach ( get_the_category() as $key => $category ) { ?>
					<li class="cat-list__item">
						<a <?php link_classes(); ?> href="<?php echo esc_url( get_term_link( $category->term_id, $category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>

	<?php if ( get_post_type() === 'post' && wpgen_options( 'single_' . get_post_type() . '_entry_footer_tags_display' ) && has_tag() ) { ?>
		<div class="post-footer__item post-footer__tags">
			<strong><?php _e( 'Post tags', 'wpgen' ); ?></strong>
			<ul class="post-footer__list tag-list">
				<?php foreach ( get_the_tags() as $key => $tag ) { ?>
					<li class="tag-list__item">
						<a <?php link_classes(); ?> href="<?php echo esc_url( get_term_link( $tag->term_id, $tag->taxonomy ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a>
					</li>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>

	<?php if ( is_user_logged_in() && current_user_can( 'edit_posts' ) && wpgen_options( 'single_' . get_post_type() . '_meta_edit_display' ) ) { ?>
		<div class="post-footer__item post-footer__edit">
			<a <?php link_classes( 'edit-link' ); ?> href="<?php echo esc_url( get_edit_post_link() ); ?>"><?php _e( 'Edit', 'wpgen' ); ?></a>
		</div>
	<?php } ?>

</footer>
