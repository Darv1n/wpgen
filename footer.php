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

<?php
	/**
	 * Hook: after_site_content.
	 *
	 * @hooked wpgen_section_content_wrapper_end           - 50
	 * @hooked wpgen_feedback_form_before_footer (if need) - 70
	 */
	do_action( 'after_site_content' ); ?>

	<footer id="footer" <?php wpgen_footer_classes(); ?> aria-label="<?php echo _x( 'Site footer', 'aria-label', 'wpgen' ); ?>">

		<?php do_action( 'wp_footer_open' ); ?>

		<?php if ( wpgen_options( 'general_footer_top_bar_display' ) ) { ?>
			<div class="footer__top-bar">
				<div <?php wpgen_container_classes( 'container-footer' ); ?>>
					<?php get_template_part( 'templates/footer/footer-top-bar' ); ?>
				</div>
			</div>
		<?php } ?>

		<div class="footer__middle-bar">
			<div <?php wpgen_container_classes( 'container-footer' ); ?>>

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

		<?php if ( wpgen_options( 'general_footer_bottom_bar_display' ) ) { ?>
			<div class="footer__bottom-bar">
				<div <?php wpgen_container_classes( 'container-footer' ); ?>>
					<?php get_template_part( 'templates/footer/footer-bottom-bar' ); ?>
				</div>
			</div>
		<?php } ?>

		<?php do_action( 'wp_footer_close' ); ?>

	</footer>

	<?php wp_footer(); ?>

</body>
</html>
