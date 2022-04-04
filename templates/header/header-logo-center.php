<?php
/**
 * Template logo center
 *
 * @package wpgen
 */

?>

<div id="header__middle-bar" class="header__middle-bar" <?php echo has_custom_header() ? 'style="background: url( ' . esc_url( get_header_image() ) . ' ) center no-repeat"' : '' ?>>

	<div <?php wpgen_container_classes(); ?>>
		<div class="row align-items-center">
			<div class="col-12 col-md-4">

				<?php do_action('wpgen_header_left_content'); ?>

			</div>
			<div class="col-12 col-md-4">

				<?php do_action('wpgen_before_site_branding'); ?>

					<?php the_wpgen_site_branding() ?>

				<?php do_action('wpgen_after_site_branding'); ?>

			</div>
			<div class="col-12 col-md-4">

				<?php do_action('wpgen_header_right_content'); ?>

			</div>
		</div>
	</div>

</div>

<div id="header__bottom-bar" class="header__bottom-bar">
	<div <?php wpgen_container_classes(); ?>>
		<div class="row align-items-center">
			<div class="col-12">

				<?php do_action('wpgen_before_site_main_menu'); ?>

				<?php if ( wpgen_options( 'general_menu_display' ) === true ) { ?>
					<div id="main-menu" <?php wpgen_main_menu_classes(); ?>>

						<?php do_action('wpgen_before_main_navigation'); ?>
						
							<nav id="main-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Site main menu', 'wpgen' ); ?>">
								
								<?php wp_nav_menu( array(
									'theme_location'	=> 'primary',
									'menu_id'			=> 'primary-navigation',
									'container'			=> ''
								) ); ?>

							</nav>

						<?php do_action('wpgen_after_main_navigation'); ?>
						
					</div>
				<?php } ?>

				<?php do_action('wpgen_after_site_main_menu'); ?>
			
			</div>
		</div>
	</div>
</div>