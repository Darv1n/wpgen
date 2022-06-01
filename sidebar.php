<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wpgen
 */

if ( ! is_active_sidebar( 'sidebar' ) ) {
	return;
} ?>

<?php if ( ! wpgen_options( 'sidebar_left_display' ) && wpgen_options( 'sidebar_right_display' ) ) { ?>
	<aside id="secondary" <?php wpgen_widget_area_classes( 'widget-area_right order-3 order-lg-3' ); ?> role="complementary">
<?php } else { ?>
	<aside id="secondary" <?php wpgen_widget_area_classes( 'widget-area_left order-2 order-lg-1' ); ?> role="complementary">
<?php } ?>

	<?php do_action( 'wpgen_before_main_sidebar' ); ?>

    <?php do_action( 'wpgen_before_left_sidebar' ); ?>

	<?php dynamic_sidebar( 'sidebar' ); ?>

    <?php do_action( 'wpgen_after_left_sidebar' ); ?>

	<?php do_action( 'wpgen_after_main_sidebar' ); ?>

</aside>
