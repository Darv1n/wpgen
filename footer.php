<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wpgen
 */

?>
			</div><!-- close .row -->
		</div><!-- close .container -->
	</div><!-- close .site__content -->

	<?php do_action( 'after_site_content' ); ?>

	<footer id="colophon" <?php wpgen_footer_classes(); ?> role="contentinfo" aria-label="<?php esc_html_e( 'Main Site Footer', 'wpgen' ); ?>">

		<?php do_action( 'wp_footer_open' ); ?>

		<!-- Footer Top Bar -->
		<div class="footer__top-bar">
			<div <?php wpgen_container_classes(); ?>>

				<?php

				if ( wpgen_options( 'general_footer_type' ) === 'footer-three-columns' ) {
					get_template_part( 'templates/footer/footer-three-columns' );
				} elseif ( wpgen_options( 'general_footer_type' ) === 'footer-four-columns' ) {
					get_template_part( 'templates/footer/footer-four-columns' );
				} else {
					get_template_part( 'templates/footer/footer-simple' );
				}

				?>

			</div>
		</div>

		<!-- Footer Bottom Bar -->
		<?php if ( wpgen_options( 'general_bottom_bar_display' ) ) { ?>
			<div class="footer__bottom-bar">
				<div <?php wpgen_container_classes(); ?>>
					<div class="row align-items-center">
						<div class="text-md-left col-12 col-sm-12 col-md-6 footer-column"><?php dynamic_sidebar( 'sidebar-footer-bottom-left' ); ?></div>
						<div class="text-md-right col-12 col-sm-12 col-md-6 footer-column"><?php dynamic_sidebar( 'sidebar-footer-bottom-right' ); ?></div>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php do_action( 'wp_footer_close' ); ?>

	</footer>
</div><!-- close .site -->

<?php wp_footer(); ?>

</body>
</html>
