<?php
/**
 * html form, shortcode and popup
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_feedback_form_action', 'ajax_feedback_form_callback' );
add_action( 'wp_ajax_nopriv_feedback_form_action', 'ajax_feedback_form_callback' );

if ( ! function_exists( 'ajax_feedback_form_callback' ) ) {

	/**
	 * Form handler.
	 */
	function ajax_feedback_form_callback() {

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
	 * @param string $form_id        form ID. Default: 'feedback-form'.
	 * @param string $form_label     form label. Default: 'Simple form'.
	 * @param string $button_classes classes for send button. Default: 'form-submit'.
	 * @param string $before_form    html before form. Default: null.
	 * @param string $after_form     html after form. Default: null.
	 *
	 * @return string
	 */
	function get_feedback_form( $form_id = 'feedback-form', $form_label = null, $button_classes = 'form-submit', $before_form = null, $after_form = null ) {

		if ( is_null( $form_label ) ) {
			$form_label = __( 'Simple form', 'wpgen' );
		}

		$available_tags = array(
			'b' => array(),
			'a' => array(
				'href'   => array(),
				'target' => array(),
				'class'  => array(),
			),
		);

		$form_id = get_title_slug( $form_id );

		$html = '<form id="' . esc_attr( $form_id ) . '" class="form ' . esc_attr( $form_id ) . '">
			<input id="form-name" class="form-name required" type="text" name="form-name" placeholder="' . __( 'What is your name?', 'wpgen' ) . '" value="">
			<input id="form-tel" class="form-tel required" type="tel" name="form-tel" inputmode="numeric" placeholder="' . __( 'What is your phone?', 'wpgen' ) . '" value="">

			<input id="form-anticheck" class="form-anticheck" type="checkbox" name="form-anticheck" style="display: none !important;" value="true" checked="checked">
			<input id="form-submitted" type="text" name="form-submitted" value="" style="display: none !important;">
			<input id="form-label" type="hidden" name="form-label" value="' . esc_attr( $form_label ) . '">

			<p class="form-confirm-text">' . sprintf( wp_kses( __( 'By submitting this form, you confirm that you agree to the storage and processing of your personal data described in our <a class="%s" href="%s" target="_blank">Privacy Policy</a>', 'wpgen' ), $available_tags ), esc_attr( implode( ' ', get_link_classes() ) ), esc_url( get_privacy_policy_url() ) ) .'</p>

			<div class="button-set">
			<button id="form-submit" class="' . esc_attr( implode( ' ', get_button_classes( $button_classes ) ) ) . '" type="submit" data-process-text="' . __( 'Sending...', 'wpgen' ) . '" data-complete-text="' . __( 'Sent', 'wpgen' ) . '" data-error-text="' . __( 'Error', 'wpgen' ) . '">' . __( 'Send', 'wpgen' ) . '</button>';

			if ( wpgen_options( 'other_whatsapp_phone' ) || wpgen_options( 'other_telegram_chat_link' ) ) {
				$html .= '<span>' . __( 'or', 'wpgen' ) . '</span>';
				if ( wpgen_options( 'other_whatsapp_phone' ) ) {
					$html .= '<a class="button button-whatsapp icon icon_whatsapp" href="' . esc_url( 'https://api.whatsapp.com/send?phone=' . preg_replace( '/(\D)/', '', wpgen_options( 'other_whatsapp_phone' ) ) ) . '" role="button">' . esc_html__( 'Write to WhatsApp', 'wpgen' ) . '</a>';
				}
				if ( wpgen_options( 'other_telegram_chat_link' ) ) {
					$html .= '<a class="button button-telegram icon icon_telegram" href="' . esc_url( wpgen_options( 'other_telegram_chat_link' ) ) . '" role="button">' . esc_html__( 'Write to Telegram', 'wpgen' ) . '</a>';
				}
			}

		$html .= '</div>';
		$html .= '</form>';

		// <input type="submit" id="form-submit" class="' . esc_attr( implode( ' ', $button_classes ) ) . '" value="' . __( 'Send', 'wpgen' ) . '">

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
		<div class="' . esc_attr( implode( ' ', get_wpgen_container_classes() ) ) . '">
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

	wp_add_inline_script( 'magnific-scripts', minify_js( $magnific_popup_form_init ) );

	$before_form = '<div id="popup" class="mfp-hide popup"><h3>' . __( 'Send request', 'wpgen' ) . '</h3>';
	$after_form  = '</div>';

	echo get_feedback_form( 'popup-form', __( 'Popup form', 'wpgen' ), $before_form, $after_form );
}
*/
