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

if ( ! function_exists( 'wpgen_comments_list' ) ) {

	/**
	 * Callback function for print comment list
	 *
	 * @param object $comment is common WordPress comment object
	 * @param array $args is custom additional parameters
	 * @param string $depth is arg for comment_reply_link().
	 *
	 */
	function wpgen_comments_list( $comment, $args, $depth ) {

		if ( get_avatar( $comment ) ) {
			$comment_classes[] = 'comment-has-avatar';
		}

		if ( empty( $args['has_children'] ) ) {
			$comment_classes[] = 'comment-is-parent';
		} else {
			$comment_classes[] = 'comment-has-parent';
		}

		?>

		<li id="comment-<?php comment_ID(); ?>" <?php comment_class( implode( ' ', $comment_classes ) ); ?>>

			<div class="comment-meta">
				<div class="comment-meta__avatar">
					<?php if ( get_avatar( $comment ) ) { ?>
						<?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
					<?php } ?>
				</div>
				<div class="comment-meta__content">

					<?php
						$url    = get_comment_author_url( $comment );
						$author = get_comment_author( $comment );

						if ( $url && $author ) { ?>
							<h4 class="comment-meta__title"><a <?php link_classes(); ?> href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $author ); ?></a></h4>
						<?php } elseif( $author ) { ?>
							<h4 class="comment-meta__title"><?php echo esc_html( $author ); ?></h4>
						<?php }
					?>

					<ul class="comment-meta__list">
						<li>
							<time class="comment-date data-title" datetime="<?php echo get_comment_date( 'Y-m-d\TH:i:sP' ); ?>" data-title="<?php echo esc_attr( __( 'Published Date', 'wpgen' ) ) ?>"><?php echo get_comment_date( 'j M, Y' ) ?></time>
						</li>
						<?php if ( $edit_link = get_edit_comment_link( $comment->comment_ID ) ) : ?>
							<li>
								<a <?php link_classes(); ?> href="<?php echo esc_url( $edit_link ); ?>"><?php esc_html_e( 'Edit', 'wpgen' ); ?></a>
							</li>
						<?php endif ?>
						
					</ul>
				</div>	
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

		</li>

		<?php

	}
}

?>

<section id="comments" class="section section_comments comments-area">

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

		<?php

			$available_tags = array(
				'b' => array(),
				'a' => array(
					'href'   => array(),
					'target' => array(),
					'class'  => array(),
				),
			);

		?>

		<form id="comment-form" class="form comment-form">

			<h2 class="form-title"><?php _e( 'Add a comment', 'wpgen' ); ?></h2>

			<textarea id="comment" name="form-comment" class="form-comment required" rows="5" cols="50" placeholder="<?php _e( 'Your comment (required)', 'wpgen' ); ?>" required></textarea>

			<div class="row">
				<div class="col-12 col-lg-6">
					<input id="form-name" class="form-name" type="text" name="form-name" placeholder="<?php _e( 'Your name (not required)', 'wpgen' ); ?>" value="">
				</div>
				<div class="col-12 col-lg-6">
					<input id="form-email" class="form-email" type="email" name="form-email" placeholder="<?php _e( 'E-mail (not required)', 'wpgen' ); ?>" value="">
				</div>
			</div>

			<input id="form-anticheck" class="form-anticheck" type="checkbox" name="form-anticheck" style="display: none !important;" value="true" checked="checked">
			<input id="form-submitted" type="text" name="form-submitted" value="" style="display: none !important;">

			<p class="form-confirm-text"><?php echo sprintf( wp_kses( __( 'By submitting this form, you confirm that you agree to the storage and processing of your personal data described in our <a class="%s" href="%s" target="_blank">Privacy Policy</a>', 'wpgen' ), $available_tags ), esc_attr( implode( ' ', get_link_classes() ) ), esc_url( get_privacy_policy_url() ) ); ?></p>

			<button id="comment-form-submit" <?php button_classes(); ?> type="submit" data-default-text="<?php _e( 'Post comment', 'wpgen' ); ?>" data-processing-text="<?php _e( 'Sending...', 'wpgen' ); ?>"><?php _e( 'Post comment', 'wpgen' ); ?></button>
		</form>

		<?php wp_enqueue_script( 'comments-handler' ); ?>

	<?php } else { ?>
		<div class="no-comments">
			<p><?php _e( 'Comments are closed', 'wpgen' ); ?></p>
		</div>
	<?php } ?>

</section>
