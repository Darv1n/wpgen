<?php
/**
 * Template footer bottom bar
 *
 * @package wpgen
 */

if ( wpgen_options( 'general_footer_type' ) === 'footer-four-columns' ) {
	$first_col_classes = array( 'col-12', 'col-sm-6', 'col-md-3' );
	$last_col_classes  = array( 'col-12', 'col-sm-6', 'col-md-7' );
} else {
	$first_col_classes = array( 'col-12', 'col-sm-6', 'col-md-4' );
	$last_col_classes  = array( 'col-12', 'col-sm-6', 'col-md-8' );
}

?>

<div <?php wpgen_archive_page_columns_wrapper_classes( 'align-items-center' ); ?>>
	<div class="<?php echo esc_attr( implode( ' ', $first_col_classes ) ); ?>">
		<?php
			if ( is_active_sidebar( 'sidebar-footer-top-left' ) ) {
				dynamic_sidebar( 'sidebar-footer-top-left' );
			} else { 
				the_wpgen_site_branding();
			}
		?>
	</div>
	<div class="<?php echo esc_attr( implode( ' ', $last_col_classes ) ); ?>">
		<?php
			if ( is_active_sidebar( 'sidebar-footer-top-right' ) ) {
				dynamic_sidebar( 'sidebar-footer-top-right' );
			} elseif( wpgen_options( 'general_menu_display' ) ) { ?>
				<div class="main-menu">
					<nav id="footer-navigation" class="footer-navigation" role="navigation" aria-label="<?php esc_html_e( 'Site main menu', 'wpgen' ); ?>">
						<?php
							$args = array(
								'theme_location' => 'primary',
								'menu_id'        => 'primary-navigation',
								'container'      => '',
							);

							wp_nav_menu( $args );
						?>
					</nav>
				</div>
			<?php }
		?>
	</div>
</div>