<?php
/**
 * Template simple footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<?php
	if ( wpgen_options( 'general_footer_type' ) === 'footer-four-columns' ) {
		$first_col_classes = array( 'col-12', 'col-sm-6', 'col-md-3' );
		$last_col_classes  = array( 'col-12', 'col-sm-6', 'col-md-7' );
	} else {
		$first_col_classes = array( 'col-12', 'col-sm-6', 'col-md-4' );
		$last_col_classes  = array( 'col-12', 'col-sm-6', 'col-md-8' );
	}
?>

<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>
	<div class="<?php echo esc_attr( implode( ' ', $first_col_classes ) ); ?>">
		<?php
			if ( is_active_sidebar( 'sidebar-footer-one' ) ) {
				dynamic_sidebar( 'sidebar-footer-one' );
			} else { ?>
				<div class="widget widget_branding">
					<?php get_template_part( 'templates/logo' ); ?>
				</div>
				<div class="widget widget_search">
					<?php get_search_form(); ?>
				</div>
			<?php }
		?>
	</div>
	<div class="<?php echo esc_attr( implode( ' ', $last_col_classes ) ); ?>">
		<?php if ( is_active_sidebar( 'sidebar-footer-two' ) ) {
			dynamic_sidebar( 'sidebar-footer-two' );
		} ?>
	</div>
</div>
