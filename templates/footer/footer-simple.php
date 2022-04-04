<?php
/**
 * Template simple footer
 *
 * @package wpgen
 */

?>

<div class="row align-items-center">

	<div class="col-12 col-sm-4"><?php the_wpgen_site_branding() ?></div>
	<div class="col-12 col-sm-8">
		
		<?php if ( wpgen_options( 'general_menu_display' ) === true ) { ?>
			<div class="site__main-menu site__main-menu_type-open site__main-menu-right footer__item">
				<nav class="main-menu" role="navigation" aria-label="<?php esc_html_e( 'Main menu of the site', 'wpgen' ); ?>">
					
					<?php wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'				=> ''
					) ); ?>
					
				</nav>
			</div>
		<?php } ?>

	</div>

</div>