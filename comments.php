<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() || ! wpgen_options( 'general_comments_display' ) ) {
	return;
}

/**
 * Callback function for print comment list
 *
 * @param object $comment is common WordPress comment object
 *
 * @param array $args is custom additional parameters
 *
 * @param string $depth is arg for comment_reply_link().
 *
 */
if ( ! function_exists( 'wpgen_comments_list' ) ) {
	function wpgen_comments_list( $comment, $args, $depth ) {

		$comment_bocy_classes[] = 'comment-body';
		if ( get_avatar( $comment ) ) {
			$comment_bocy_classes[] = 'comment-body-avatars';
		}

		?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
			<div id="div-comment-<?php comment_ID(); ?>" class="<?php esc_attr_e( implode( ' ', $comment_bocy_classes ) ); ?>">

				<?php if ( get_avatar( $comment ) ) { ?>
					<div class="comment-author comment-author-avatar">
						<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
					</div>
				<?php } ?>

				<div class="entry__meta entry__meta_inline">
					<ul class="meta comment-meta">
						<li class="meta__item meta__item_autor">
							<?php comment_author_link(); ?>
						</li>

						<li class="meta__item meta__item_date">
							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php printf( esc_html__( '%1$s in %2$s', 'wpgen' ), get_comment_date( 'j F, Y' ), get_comment_time( 'H:i' ) ); ?>
								</time>
							</a>
						</li>

						<?php do_action( 'add_comment_meta_list' ); ?>

						<?php edit_comment_link( __( 'Edit', 'wpgen' ), '<li class="meta__item meta__item_edit"><span class="edit-link">', '</span></li>' ); ?>

					</ul>
				</div>

				<div class="comment-content">
					<?php comment_text(); ?>
				</div>

				<div class="reply">
					<?php

						$custom_args = array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
						);

						comment_reply_link( array_merge( $args, $custom_args ) );

						?>
				</div>

				<?php if ( '0' == $comment->comment_approved ) { ?>
					<p class="comment-awaiting-moderation"><?php esc_html__( 'Your comment is awaiting moderation', 'wpgen' ); ?></p>
				<?php } ?>

			</div>
		</li>

		<?php

	}
}

?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) { ?>

		<h3 class="comments-title"><?php esc_html__( 'Comments:&nbsp;', 'wpgen' ); ?><span class="count-comments"><?php comments_number( '0', '1', '%' ); ?></span></h3>

		<ol class="comment-list">
			<?php

				$comm_args = array(
					'avatar_size' => '60',
					'callback'    => 'wpgen_comments_list',
				);

				wp_list_comments( $comm_args );

				?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
			<div class="row no-gutters justify-content-between comment-navigation">
				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Previous comments', 'wpgen' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'New comments', 'wpgen' ) ); ?></div>
			</div>
		<?php } ?>

	<?php } ?>

	<?php if ( comments_open() ) { ?>
		<?php comment_form(); ?>
	<?php } else { ?>
		<div class="no-comments">
			<p><?php _e( 'Comments are closed', 'wpgen' ); ?></p>
		</div>
	<?php } ?>

</div>
