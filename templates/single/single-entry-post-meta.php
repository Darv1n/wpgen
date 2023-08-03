<?php
/**
 * Template part for displaying single entry post meta.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<ul class="post-meta" aria-label="<?php _e( 'Post meta information', 'wpgen' ); ?>">

	<?php do_action( 'wpgen_before_single_entry_post_meta' ); ?>

	<?php if ( wpgen_options( 'single_' . get_post_type() . '_meta_author_display' ) === true ) { ?>
		<li class="post-meta__item icon icon_before icon_user">
			<a <?php link_classes( 'post-meta__link' ); ?> href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) ?>" rel="author"><?php the_author(); ?></a>
		</li>
	<?php } ?>

	<?php if ( wpgen_options( 'single_' . get_post_type() . '_meta_date_display' ) === true ) { ?>
		<li class="post-meta__item icon icon_before icon_calendar">
			<time class="post-date post-date-published data-title" datetime="<?php echo get_the_date( 'Y-m-d\TH:i:sP' ); ?>" data-title="<?php _e( 'Publication date', 'wpgen' ); ?>"><?php echo get_the_date( 'j F, Y' ); ?></time>
			<?php if ( wpgen_options( 'single_' . get_post_type() . '_meta_date_modified_display' ) && get_the_modified_date( 'j M, Y' ) !== get_the_date( 'j M, Y' ) ) { ?>
				<time class="post-date post-date-modified data-title" datetime="<?php the_modified_date( 'Y-m-d\TH:i:sP' ); ?>" data-title="<?php _e( 'Modification date', 'wpgen' ); ?>">(<?php the_modified_date( 'j F, Y' ); ?>)</time>
			<?php } ?>
		</li>
	<?php } ?>

	<?php if ( get_post_type() === 'post' ) { ?>
		<?php if ( wpgen_options( 'single_' . get_post_type() . '_meta_cats_display' ) === true && has_category() ) { ?>
			<li class="post-meta__item icon icon_before icon_folder">
				<?php foreach ( get_the_category() as $key => $category ) { ?>
					<a <?php link_classes( 'post-meta__link' ); ?> href="<?php echo esc_url( get_term_link( $category->term_id, $category->taxonomy ) ); ?>"><?php echo esc_html( $category->name ); ?></a>
				<?php } ?>
			</li>
		<?php } ?>
		<?php if ( wpgen_options( 'single_' . get_post_type() . '_meta_tags_display' ) === true && has_tag() ) { ?>
			<li class="post-meta__item icon icon_before icon_tag">
				<?php foreach ( get_the_tags() as $key => $tag ) { ?>
					<a <?php link_classes( 'post-meta__link' ); ?> href="<?php echo esc_url( get_term_link( $tag->term_id, $tag->taxonomy ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
				<?php } ?>
			</li>
		<?php } ?>
	<?php } ?>

	<?php if ( wpgen_options( 'single_' . get_post_type() . '_meta_time_display' ) === true ) { ?>
		<li class="post-meta__item icon icon_before icon_clock data-title" data-title="<?php _e( 'Reading time', 'wpgen' ); ?>">
			<?php echo read_time_estimate( get_the_content() ) . ' ' . __( 'min.', 'wpgen' ); ?>
		</li>
	<?php } ?>

	<?php if ( wpgen_options( 'single_' . get_post_type() . '_meta_comments_display' ) === true ) { ?>
		<li class="post-meta__item icon icon_before icon_comment">
			<a <?php link_classes( 'post-meta__link' ); ?> href="<?php echo esc_url( get_comments_link() ); ?>" rel="bookmark"><?php _e( 'Comments', 'wpgen' ) ?>: <?php echo get_comments_number(); ?></a>
		</li>
	<?php } ?>

	<?php do_action( 'wpgen_after_single_entry_post_meta' ); ?>

	<?php if ( is_user_logged_in() && current_user_can( 'edit_posts' ) && wpgen_options( 'single_' . get_post_type() . '_meta_edit_display' ) === true ) { ?>
		<li class="post-meta__item icon icon_before icon_pen">
			<a <?php link_classes( 'post-meta__link' ); ?> href="<?php echo esc_url( get_edit_post_link() ); ?>"><?php _e( 'Edit', 'wpgen' ) ?></a>
		</li>
	<?php } ?>

</ul>
