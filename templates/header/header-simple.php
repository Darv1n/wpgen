<?php
/**
 * Template header center
 *
 * @package wpgen
 */

?>

<div id="header__middle-bar" class="header__middle-bar" <?php echo has_custom_header() ? 'style="background: url( ' . esc_url( get_header_image() ) . ' ) center/cover no-repeat" role="img"' : ''; ?>>

	<div <?php wpgen_container_classes( 'container-header' ); ?>>
		<div class="d-flex justify-content-between align-items-center relative">

			<?php do_action( 'wpgen_before_site_branding' ); ?>

				<?php the_wpgen_site_branding(); ?>

			<?php do_action( 'wpgen_after_site_branding' ); ?>

			<?php do_action( 'wpgen_before_site_main_menu' ); ?>

			<!-- Header Main Menu -->
			<?php if ( wpgen_options( 'general_menu_display' ) ) { ?>
				<div id="main-menu" <?php wpgen_main_menu_classes(); ?>>

					<?php do_action( 'wpgen_before_main_navigation' ); ?>

						<nav id="main-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Site main menu', 'wpgen' ); ?>">

							<?php

								$args = array(
									'theme_location' => 'primary',
									'menu_id'        => 'primary-navigation',
									'container'      => '',
								);

								wp_nav_menu( $args );

								?>

						</nav>

					<?php do_action( 'wpgen_after_main_navigation' ); ?>

				</div>
			<?php } ?>

			<?php do_action( 'wpgen_after_site_main_menu' ); ?>

		</div>
	</div>

</div>
