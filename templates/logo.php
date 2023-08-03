<?php
/**
 * Template part for displaying site logo
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<?php

// Check if the image is set in the customizer settings or display the text.
if ( has_custom_logo() ) {
	the_custom_logo();
} else {
	// For all pages except the main page, display a link to it.
	if ( ( is_front_page() || is_home() ) && ! is_paged() ) { ?>
		<div class="logo">
			<strong class="logo__title"><?php bloginfo( 'name' ); ?></strong>
			<p class="logo__description"><?php bloginfo( 'description' ); ?></p>
		</div>
	<?php } else { ?>
		<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<strong class="logo__title"><?php bloginfo( 'name' ); ?></strong>
			<p class="logo__description"><?php bloginfo( 'description' ); ?></p>
		</a>
	<?php }
}
