<?php
/**
 * html form, shortcode and popup
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_form_action', 'form_ajax_action_callback' );
add_action( 'wp_ajax_nopriv_form_action', 'form_ajax_action_callback' );

if ( ! function_exists( 'form_ajax_action_callback' ) ) {

	/**
	 * Form handler.
	 */
	function form_ajax_action_callback() {

		parse_str( $_POST['content'], $data ); // Create an array that contains the values of the fields of the filled form.

		$errors   = array(); // Error array.
		$message  = array(); // Letter array.
		$home_url = preg_replace( '/^(http[s]?):\/\//', '', get_home_url() );

		// Check nonce. If check fails, block sending.
		/*if ( ! wp_verify_nonce( $data['nonce'], 'form-nonce' ) ) {
			wp_die( __( 'Data sent from a different address', 'wpgen' ) );
		}*/

		// Check for spam. If hidden field is full or the check is cleared, block sending.
		if ( ! $data['form-anticheck'] || ! empty( $data['form-submitted'] ) ) {
			$errors['submit'] = __( 'Your comment does not pass the spam filter. If an error occurs, please write to team@zolin.digital', 'wpgen' );
			wp_send_json_error( $errors );
		}

		// Check name fields, if empty, write message to error array.
		if ( ! isset( $data['form-name'] ) || empty( $data['form-name'] ) ) {
			$errors['name'] = __( 'Please enter your name', 'wpgen' );
		} else {
			$message[ __( 'Name', 'wpgen' ) ] = sanitize_text_field( $data['form-name'] );
		}

		$tels = get_option( 'tels', array() );

		if ( ! isset( $data['form-tel'] ) || empty( $data['form-tel'] ) ) {
			$errors['tel'] = __( 'Please enter a phone number', 'wpgen' );
		} elseif ( in_array( sanitize_text_field( $data['form-tel'] ), $tels ) ) {
			$errors['tel'] = __( 'You have already sent a request from this phone number. If an error occurs, please write to team@zolin.digital', 'wpgen' );
		} else {
			$message[ __( 'Phone', 'wpgen' ) ] = sanitize_text_field( $data['form-tel'] );
			$tels[]                            = sanitize_text_field( $data['form-tel'] );
			update_option( 'tels', array_unique( $tels ), false );
		}

		if ( isset( $data['form-label'] ) ) {
			$message[ __( 'Form name', 'wpgen' ) ] = sanitize_text_field( $data['form-label'] );
		} else {
			$message[ __( 'Form name', 'wpgen' ) ] = __( 'Simple form', 'wpgen' );
		}

/*		// Check email field, if it is empty, write message in error array.
		if ( ! isset( $data['form-email'] ) || empty( $data['form-email'] ) ) {
			$errors['email'] = __( 'Please enter your email address', 'wpgen' );
		} elseif ( ! preg_match( '/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,8}$/i', $data['form-email'] ) ) {
			$errors['email'] = __( 'Email address is incorrect', 'wpgen' );
		} else {
			$form-email = sanitize_email( $data['form-email'] );
		}

		// Check message fields, if empty, write message in error array.
		if ( ! isset( $data['form-message'] ) || empty( $data['form-message'] ) ) {
			$errors['message'] = __( 'Please enter your message', 'wpgen' );
		} else {
			$message[ __( 'Message', 'wpgen' ) ] = sanitize_textarea_field( $data['form-message'] );
		}*/

		$form_subject = __( 'Application from site', 'wpgen' ) . ' ' . $home_url;

		// Сheck error array, if it's not empty, return json error. Otherwise send email.
		if ( $errors ) {
			wp_send_json_error( $errors );
		} else {
			// Specify addressees.
			$email_to[] = 'webdev1992@yandex.ru';
			if ( ! empty( wpgen_options( 'other_email' ) ) ) {
				$email_to[] = wpgen_options( 'other_email' );
			}

			$email_to   = array_unique( $email_to );
			$email_from = 'team@zolin.digital';
			$headers    = 'From: ' . $home_url . ' <' . $email_from . '>' . "\r\n" . 'Reply-To: ' . $email_from;
			$body       = '';

			foreach ( $message as $key => $value ) {
				$body .= $key . ': ' . $value . "\r\n";
			}
			
			// Send email.
			$wp_mail = wp_mail( $email_to, $form_subject, $body, $headers );

			// Return json about successful sending.
			if ( $wp_mail ) {
				wp_send_json_success( __( 'A message has been sent. We will contact you shortly', 'wpgen' ) );
			} else {
				$errors['submit'] = __( 'An unknown error occurred while submitting the form. Please write to team@zolin.digital', 'wpgen' );
				wp_send_json_error( $errors );
			}
		}

		// Kill ajax process.
		wp_die();
	}
}

if ( ! function_exists( 'get_feedback_form' ) ) {

	/**
	 * Return HTML feedback form
	 *
	 * @param string $form_id       form ID. Default: 'feedback-form'.
	 * @param string $form_label    form label. Default: 'Simple form'.
	 * @param string $before_form   html before form. Default: null.
	 * @param string $after_form    html after form. Default: null.
	 * @param array $button_classes array classes for send button. Default: get_button_classes().
	 *
	 * @return string
	 */
	function get_feedback_form( $form_id = 'feedback-form', $form_label = null, $before_form = null, $after_form = null, $button_classes = null ) {

		if ( is_null( $button_classes ) ) {
			$button_classes = get_button_classes();
		}

		$button_classes[] = 'form-submit';

		if ( is_null( $form_label ) ) {
			$form_label = __( 'Simple form', 'wpgen' );
		}

		if ( determine_locale() === 'ru_RU' ) {
			$tel_placeholder = '+7 (___) ___-__-__';
		} else {
			$tel_placeholder = '___-__-__';
		}

		$available_tags = array(
			'b' => array(),
			'a' => array(
				'href'   => array(),
				'target' => array(),
				'class'  => array(),
			),
		);

		$privacy_policy_url = get_privacy_policy_url();

		if ( empty( $privacy_policy_url ) && is_multisite() && ! is_main_site() ) {
			switch_to_blog( 1 );
			$privacy_policy_url = get_privacy_policy_url();
			restore_current_blog();
		}

		if ( empty( $privacy_policy_url ) ) {
			$privacy_policy_url = get_home_url();
		}

		$form_id = get_title_slug( $form_id );

		$html = '<form id="' . esc_attr( $form_id ) . '" class="form ' . esc_attr( $form_id ) . '">
			<input type="text" name="form-name" id="form-name" class="required form-name" placeholder="' . __( 'What is your name?', 'wpgen' ) . '" value="">
			<input type="tel" name="form-tel" id="form-tel" class="required form-tel" inputmode="numeric" placeholder="' . $tel_placeholder .'" value="">

			<input type="checkbox" name="form-anticheck" id="form-anticheck" class="form-anticheck" style="display: none !important;" value="true" checked="checked">
			<input type="text" name="form-submitted" id="form-submitted" value="" style="display: none !important;">
			<input type="hidden" name="form-label" id="form-label" value="' . $form_label . '">

			<p class="description small">' . sprintf( wp_kses( __( 'By clicking the button, you consent to the processing of <a class="link link-unborder" href="%s" target="_blank">personal data</a>', 'wpgen' ), $available_tags ), esc_url( $privacy_policy_url ) ) .'</p>

			<button type="submit" id="form-submit" class="' . esc_attr( implode( ' ', $button_classes ) ) . '" data-process-text="' . __( 'Sending...', 'wpgen' ) . '" data-complete-text="' . __( 'Sent', 'wpgen' ) . '" data-error-text="' . __( 'Error', 'wpgen' ) . '">' . __( 'Send', 'wpgen' ) . '</button>
		';

		if ( wpgen_options( 'other_whatsapp_phone' ) || wpgen_options( 'other_telegram_nick' ) ) {
			$html .= '<hr>' . __( 'or', 'wpgen' ) . '<hr>';
			$html .= '<div class="button-set">';
			if ( wpgen_options( 'other_whatsapp_phone' ) ) {
				$html .= '<a class="btn btn-whatsapp" href="https://api.whatsapp.com/send?phone=' . esc_html( preg_replace( '/(\D)/', '', wpgen_options( 'other_whatsapp_phone' ) ) ) . '">' . esc_html__( 'Write to Whatsapp', 'wpgen' ) . '</a>';
			}
			if ( wpgen_options( 'other_telegram_nick' ) ) {
				$html .= '<a class="btn btn-telegram" href="' . esc_url( 'https://t.me/' . esc_html( wpgen_options( 'other_telegram_nick' ) ) ) . '">' . esc_html__( 'Write to Telegram', 'wpgen' ) . '</a>';
			}
			$html .= '</div>';
		}

		$html .= '</form>';

		// <input type="submit" id="form-submit" class="' . implode( ' ', array_map( 'esc_attr', $button_classes ) ) . '" value="' . __( 'Send', 'wpgen' ) . '">

		$html = $before_form . $html . $after_form;

		$html = apply_filters( 'get_feedback_form', $html );

		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'handler-form' );

		return $html;
	}
}

/*
// Usage: add feedback form before footer
add_action( 'after_site_content', 'add_feedback_form_before_footer', 10 );
function add_feedback_form_before_footer() {

	$html = '<section class="section section-feedback">
		<div class="' . implode( ' ', array_map( 'esc_attr', get_wpgen_container_classes() ) ) . '">
			<div class="title-wrapper">
				<h2 class="section-title">' . __( 'Feedback form', 'wpgen' ) . '</h2>
			</div>
			' . get_feedback_form( 'feedback-form', __( 'Form before footer', 'wpgen' ) ) . '
		</div>
	</section>';

	echo $html;
}
*/

/*
// Usage: add popup form after footer
add_action( 'wp_footer', 'add_popup_form_before_footer', 10 );
function add_popup_form_before_footer() {

	wp_enqueue_style( 'magnific-styles' );
	wp_enqueue_script( 'magnific-scripts' );

	$magnific_popup_form_init = 'jQuery(function($) {
		$(".popup-button").magnificPopup({
			closeBtnInside: true,
			type: "inline",
			zoom: {
				enabled: true,
				duration: 200,
				easing: "ease-in-out",
			},
			preload: [0, 2],
		});
	});';

	wp_add_inline_script( 'magnific-scripts', $magnific_popup_form_init );

	$before_form = '<div id="popup" class="mfp-hide popup"><h3>' . __( 'Send request', 'wpgen' ) . '</h3>';
	$after_form  = '</div>';

	echo get_feedback_form( 'popup-form', __( 'Popup form', 'wpgen' ), $before_form, $after_form );
}
*/
