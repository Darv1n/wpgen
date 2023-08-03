<?php
/**
 * Template part for displaying button menu.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<?php

$menu_classes = get_wpgen_menu_toggle_classes();

if ( in_array( 'toggle-icon', $menu_classes, true ) ) { ?>
	<button id="menu-toggle" class="<?php echo esc_attr( implode( ' ', $menu_classes ) ); ?>" data-icon-on="icon_xmark" data-icon-off="icon_bars">
<?php } else { ?>
	<button id="menu-toggle" class="<?php echo esc_attr( implode( ' ', $menu_classes ) ); ?>">
<?php } ?>
	<?php if ( in_array( wpgen_options( 'general_menu_button_type' ), array( 'icon', 'button-icon' ), true ) ) { ?>
		<i class="icon"></i>
	<?php } else {
		_e( 'Menu', 'wpgen' );
	} ?>
</button>