<?php
/**
 * Template part for displaying cookie accepter.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wpgen
 */
 ?>

<div id="cookie" class="cookie" style="display: none">
	<div <?php wpgen_container_classes(); ?>>
		<?php sprintf( wp_kses( '<p class="cookie__text">' . _e( 'By continuing to use %s, you agree to the use of cookies. More information can be found in the <a class="%s" href="%s" target="_blank">Privacy Policy</a>' .  '</p>', 'wpgen' ), kses_available_tags() ), wp_parse_url( get_home_url() )['host'], esc_attr( implode( ' ', get_link_classes() ) ), esc_url( get_privacy_policy_url() ) ); ?>
		<span id="cookie-action" class="cookie__action" role="button"><i class="icon icon_xmark"></i></span>
	</div>
</div>