<?php
/**
 * Template part for displaying post navigation.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<nav class="navigation post-navigation" role="navigation">
	<div class="row">
		<div class="col-12 col-md-6">
			<?php echo get_previous_post_link( '<div class="post-navigation__item post-navigation__item_previous">%link</div>', '<span class="post--title">← %title</span>' ); ?>;
		</div>
		<div class="col-12 col-md-6">
			<?php echo get_next_post_link( '<div class="post-navigation__item post-navigation__item_next">%link</div>', '<span class="post--title">%title →</span>' ); ?>;
		</div>
	</div>
</nav>
