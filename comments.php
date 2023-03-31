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

	<?php if ( comments_open() ) {

		global $post;
		$post_id       = $post->ID;
		$commenter     = wp_get_current_commenter();
		$user          = wp_get_current_user();
		$user_identity = $user->exists() ? $user->display_name : '';
		$req           = get_option( 'require_name_email' );
		$span_req      = $req ? ' <span class="required">*</span>' : '';
		$aria_req      = $req ? ' required="required"' : '';
		$html5         = 'html5';
		$commenter     = wp_get_current_commenter();

		$args = array(
			'fields'              => array(
				'author' => '<div class="col-12 col-lg-6 comment-form-author">
					<label for="author">' . __( 'Name' ) . $span_req . '</label>
					<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" autocomplete="name"' . $aria_req . '/>
				</div>',
				'email'  => '<div class="col-12 col-lg-6 comment-form-email">
					<label for="email">' . __( 'Email' ) . $span_req . '</label>
					<input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" autocomplete="email" aria-describedby="email-notes"' . $aria_req . '/>
				</div>',
				'cookies' => '<div class="col-12 comment-form-cookies-consent">
					<input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes" checked="checked"/>
					<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.' ) . '</label>
				</div>',
			),
			'class_form'           => 'row comment-form',
			'comment_field'        => '<div class="col-12 comment-form-comment">
				<label for="comment">' . _x( 'Comment', 'wpgen' ) . '<span class="required">*</span></label>
				<textarea id="comment" name="comment" cols="45" rows="8"  aria-required="true" required="required" maxlength="65525" spellcheck="true"></textarea>
			</div>',
			'must_log_in'          => '<div class="col-12 must-log-in">' .
				 sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '
			 </div>',
			'logged_in_as'         => '<div class="col-12 logged-in-as">' .
				 sprintf( __( '<a href="%1$s" aria-label="Logged in as %2$s. Edit your profile.">Logged in as %2$s</a>. <a href="%3$s">Log out?</a>' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '
			 </div>',
			'comment_notes_after'  => '',
			'comment_notes_before' => '<div class="col-12 comment-notes">
				<span id="email-notes">' . __( 'Your email address will not be published.' ) . '</span>
			</div>',
			'submit_field'         => '<div class="col-12 form-submit">%1$s %2$s</div>',
			'class_submit'         => 'submit icon icon_envelope',
			'title_reply'          => esc_html__( 'Leave a Reply', 'wpgen' ),
			'label_submit'         => esc_html__( 'Post Comment', 'wpgen' )
		);

		comment_form( $args );
	} else { ?>
		<div class="no-comments">
			<p><?php _e( 'Comments are closed', 'wpgen' ); ?></p>
		</div>
	<?php } ?>

</section>
