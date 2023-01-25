<?php
/**
 * Template four columns footer
 *
 * @package wpgen
 */

?>

<div <?php wpgen_archive_page_columns_wrapper_classes(); ?>>
	<div class="col-12 col-sm-6 col-md-3 footer-column">
		<?php
			if ( is_active_sidebar( 'sidebar-footer-one' ) ) {
				dynamic_sidebar( 'sidebar-footer-one' );
			} else { ?>
				<div class="widget widget_branding">
					<?php echo do_shortcode( '[wpgen-logo]' ); ?>
				</div>
				<div class="widget widget_search">
					<?php get_search_form(); ?>
				</div>
			<?php }
		?>
	</div>
	<div class="col-12 col-sm-6 col-md-3 footer-column">
		<?php
			if ( is_active_sidebar( 'sidebar-footer-two' ) ) {
				dynamic_sidebar( 'sidebar-footer-two' );
			}
		?>
	</div>
	<div class="col-12 col-sm-6 col-md-3 footer-column">
		<?php
			if ( is_active_sidebar( 'sidebar-footer-three' ) ) {
				dynamic_sidebar( 'sidebar-footer-three' );
			}
		?>
	</div>
	<div class="col-12 col-sm-6 col-md-3 footer-column">
		<?php
			if ( is_active_sidebar( 'sidebar-footer-four' ) ) {
				dynamic_sidebar( 'sidebar-footer-four' );
			} else { ?>
				<div class="widget widget_footer_contacts">
					<h3 class="widget-title"><?php esc_html_e( 'Contacts', 'wpgen' ); ?></h3>
					<?php echo do_shortcode( '[wpgen-contacts-list]' ); ?>
					<?php echo do_shortcode( '[wpgen-social-list]' ); ?>
				</div>
			<?php }
		?>
	</div>
</div>