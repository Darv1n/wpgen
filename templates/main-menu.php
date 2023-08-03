<?php
/**
 * Template part for displaying main menu.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<?php $args = array(
	'theme_location' => 'primary',
	'menu_id'        => 'primary-navigation',
	'container'      => '',
); ?>

<div id="main-menu" <?php wpgen_main_menu_classes(); ?>>

	<?php get_template_part( 'templates/button-menu-toggle' ); ?>

	<nav id="main-navigation" class="main-navigation" role="navigation" aria-label="<?php _e( 'Site main menu', 'wpgen' ); ?>">
		<?php wp_nav_menu( $args ); ?>
	</nav>

</div>