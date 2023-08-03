<?php
/**
 * Template part for displaying archive post pagination.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<?php

global $paged;
global $wp_query;

$pages     = $wp_query->max_num_pages;
$range     = 2;
$showitems = ( $range * 2 ) + 1;

if ( ! $pages || empty( $paged ) ) {
	$paged = 1;
}

if ( (int) $pages === 1 ) {
	return;
} ?>

<nav class="navigation posts-navigation posts-navigation_<?php echo esc_attr( wpgen_options( 'archive_' . get_post_type() . '_pagination' ) ); ?>" data-max-pages="<?php echo esc_attr( $pages ); ?>" role="navigation" aria-label="<?php _e( 'Site post navigation', 'wpgen' ); ?>">

	<?php if ( wpgen_options( 'archive_' . get_post_type() . '_pagination' ) === 'numeric' ) {

		// First page.
		if ( $paged > 3 ) { ?>
			<a class="<?php button_classes( 'posts-navigation__item posts-navigation__item_first button-small icon icon_center icon_chevron-left' ); ?>" href="<?php echo esc_url( get_pagenum_link( 1 ) ); ?>" role="button">-1</a>
		<?php }

		// The main link output loop.
		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {

				if ( $paged === $i ) { ?>
					<span class="<?php button_classes( 'button-small button-disabled posts-navigation__item posts-navigation__item_current' ); ?>"><?php echo $i; ?></span>
				<?php } else {

					if ( $paged === $i ) {
						$rel     = '';
						$classes = 'button-small button-disabled posts-navigation__item posts-navigation__item_current';
					} elseif ( $paged + 1 === $i ) {
						$rel     = ' rel="next"';
						$classes = 'button-small posts-navigation__item posts-navigation__item_next';
					} elseif ( $paged > 1 && $paged - 1 === $i ) {
						$rel     = ' rel="prev"';
						$classes = 'button-small posts-navigation__item posts-navigation__item_prev';
					} else {
						$rel     = '';
						$classes = 'button-small posts-navigation__item';
					} ?>

					<a class="<?php button_classes( $classes ); ?>" href="<?php echo esc_url( get_pagenum_link( $i ) ); ?>" role="button"<?php echo $rel; ?>><?php echo $i; ?></a>

				<?php }
			}
		}

		// Last Page.
		if ( $pages > 5 && $paged < $pages - 2 ) { ?>
			<a class="<?php button_classes( 'posts-navigation__item posts-navigation__item_last button-small icon icon_center icon_chevron-right' ); ?>" href="<?php echo esc_url( get_pagenum_link( $pages ) ); ?>" role="button">+1</a>
		<?php }

	} else { ?>

		<div class="row">

			<?php if ( get_next_posts_link() ) { ?>
				<div class="col-12 col-md-6">
					<div class="posts-navigation__item_prev">
						<?php next_posts_link( __( 'Older Posts', 'wpgen' ) ); ?>
					</div>
				</div>
			<?php } ?>

			<?php if ( get_previous_posts_link() ) { ?>
				<div class="col-12 col-md-6">
					<div class="posts-navigation__item_next">
						<?php previous_posts_link( __( 'Newer Posts', 'wpgen' ) ); ?>
					</div>
				</div>
			<?php } ?>

		</div>
	}

</nav>