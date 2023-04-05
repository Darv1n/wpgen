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

		$form_fields = get_feedback_form_fields();

		if ( ! $form_fields ) {
			$errors['submit'] = __( 'An error occurred while processing form data, please write to team@zolin.digital', 'wpgen' );
			wp_send_json_error( $errors );
		}

		// Check nonce. If check fails, block sending.
		/*if ( ! wp_verify_nonce( $data['nonce'], 'form-nonce' ) ) {
			wp_die( __( 'Data sent from a different address', 'wpgen' ) );
		}*/

		// Check for spam. If hidden field is full or the check is cleared, block sending.
		if ( ! $data['form-anticheck'] || ! empty( $data['form-submitted'] ) ) {
			$errors['submit'] = __( 'Your comment does not pass the spam filter. If an error occurs, please write to team@zolin.digital', 'wpgen' );
			wp_send_json_error( $errors );
		}

		foreach ( $form_fields as $form_field_id => $form_field ) {

			// Колбек для текста ошибки.
			if ( isset( $form_field['text_error'] ) ) {
				$text_error = $form_field['text_error'];
			} else {
				if ( $form_field['type'] === 'tel' ) {
					$text_error = __( 'Please enter a phone number', 'wpgen' );
				} elseif ( $form_field['type'] === 'email' ) {
					$text_error = __( 'Please enter an email address', 'wpgen' );
				} elseif ( $form_field['type'] === 'textarea' ) {
					$text_error = __( 'Please enter your message', 'wpgen' );
				} else {
					$text_error = __( 'Please complete this field', 'wpgen' );
				}
			}

			// Обработка полей.
			if ( $form_field['type'] === 'email' ) {

				if ( isset( $data[ $form_field_id ] ) && ! empty( $data[ $form_field_id ] ) ) {
					if ( ! is_email( $data[ $form_field_id ] ) ) {
						$errors[ $form_field_id ] = __( 'Email address is incorrect', 'wpgen' );
					} else {
						$message[ $form_field['title'] ] = sanitize_email( $data[ $form_field_id ] );
					}
				} elseif ( $form_field['required'] ) {
					$errors[ $form_field_id ] = $text_error;
				}

			} else {

				if ( isset( $data[ $form_field_id  ] ) && ! empty( $data[ $form_field_id ] ) ) {
					if ( $form_field['type'] === 'textarea' ) {
						$message[ $form_field['title'] ] = sanitize_textarea_field( $data[ $form_field_id ] );
					} else {
						$message[ $form_field['title'] ] = sanitize_text_field( $data[ $form_field_id ] );
					}
				} elseif ( $form_field['required'] ) {
					$errors[ $form_field_id ] = $text_error;
				}

			}
		}

		if ( isset( $data['form-title'] ) && ! empty( $data['form-title'] ) ) {
			$message[ __( 'Form name', 'wpgen' ) ] = sanitize_text_field( $data['form-title'] );
		} else {
			$message[ __( 'Form name', 'wpgen' ) ] = __( 'Simple form', 'wpgen' );
		}

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

if ( ! function_exists( 'get_feedback_form_fields' ) ) {

	/**
	 * Return array with the default feedback form fields.
	 *
	 * @param string $control array key to get one value.
	 *
	 * @return array
	 */
	function get_feedback_form_fields( $control = null ) {

		$form_fields = array(
			'form-name' => array(
				'type'        => 'text',
				'required'    => true,
				'text_error'  => __( 'Please enter your name', 'wpgen' ),
				'title'       => __( 'Name', 'wpgen' ),
				'placeholder' => __( 'What is your name?', 'wpgen' ),
			),
			'form-tel' => array(
				'type'        => 'tel',
				'required'    => true,
				'text_error'  => __( 'Please enter your phone number', 'wpgen' ),
				'title'       => __( 'Phone', 'wpgen' ),
				'placeholder' => __( 'What is your phone?', 'wpgen' ),
			),
		);

		$form_fields = apply_filters( 'get_feedback_form_fields', $form_fields );

		// Return controls.
		if ( is_null( $control ) ) {
			return $form_fields;
		} elseif ( ! isset( $form_fields[ $control ] ) || empty( $form_fields[ $control ] ) ) {
			return false;
		} else {
			return $form_fields[ $control ];
		}
	}
}

/*// Usage: change feedback form fields.
add_filter( 'get_feedback_form_fields', 'change_get_feedback_form_fields', 10 );
if ( ! function_exists( 'change_get_feedback_form_fields' ) ) {
	function change_get_feedback_form_fields( $form_fields ) {

		$form_fields['form-email'] = array(
			'type'        => 'email',
			'required'    => true,
			'placeholder' => __( 'What is your email? (required)', 'wpgen' ),
		);

		return $form_fields;
	}
}*/

if ( ! function_exists( 'get_feedback_form' ) ) {

	/**
	 * Return HTML feedback form
	 *
	 * @param string $form_id        form ID. Default: 'feedback-form'.
	 * @param string $form_title     form label. Default: 'Simple form'.
	 * @param string $button_classes classes for send button. Default: 'form-submit'.
	 * @param string $before_form    html before form. Default: null.
	 * @param string $after_form     html after form. Default: null.
	 *
	 * @return string
	 */
	function get_feedback_form( $form_id = 'feedback-form', $form_title = null, $button_classes = 'form-submit', $before_form = null, $after_form = null ) {

		if ( is_null( $form_title ) ) {
			$form_title = __( 'Simple form', 'wpgen' );
		}

		$available_tags = array(
			'b' => array(),
			'a' => array(
				'href'   => array(),
				'target' => array(),
				'class'  => array(),
			),
		);

		$form_id     = get_title_slug( $form_id );
		$form_fields = get_feedback_form_fields();

		if ( ! $form_fields ) {
			return '';
		}

		$html = '<form id="' . esc_attr( $form_id ) . '" class="form ' . esc_attr( $form_id ) . '">';

			$html = apply_filters( 'before_feedback_form_fields', $html );

			foreach ( $form_fields as $key => $form_field ) {
				$html .= '<label class="form-label" for="' . $key . '">';

					if ( $form_field['required'] ) {
						$required = ' required';
					} else {
						$required = '';
					}

					if ( $form_field['type'] === 'textarea' ) {
						$html .= '<textarea id="' . $key . '" class="form-textarea' . $required . '" name="' . $key . '" rows="5" cols="33" placeholder="' . $form_field['placeholder'] . '" value=""' . $required . '></textarea>';
					} else {
						$html .= '<input id="' . $key . '" class="form-input' . $required . '" type="text" name="' . $key . '" placeholder="' . $form_field['placeholder'] . '" value="" ' . $required . '>';
					}

				$html .= '</label>';
			}

			$html = apply_filters( 'after_feedback_form_fields', $html );

			$html .= '<input id="form-anticheck" class="form-anticheck" type="checkbox" name="form-anticheck" style="display: none !important;" value="true" checked="checked">';
			$html .= '<input id="form-submitted" type="text" name="form-submitted" value="" style="display: none !important;">';
			$html .= '<input id="form-title" type="hidden" name="form-title" value="' . esc_attr( $form_title ) . '">';
			$html .= '<p class="form-confirm-text">' . sprintf( wp_kses( __( 'By submitting this form, you confirm that you agree to the storage and processing of your personal data described in our <a class="%s" href="%s" target="_blank">Privacy Policy</a>', 'wpgen' ), $available_tags ), esc_attr( implode( ' ', get_link_classes() ) ), esc_url( get_privacy_policy_url() ) ) .'</p>';

			$html .= '<div class="button-set">';
				$html .= '<button id="form-submit" class="' . esc_attr( implode( ' ', get_button_classes( $button_classes ) ) ) . '" type="submit" data-process-text="' . __( 'Sending...', 'wpgen' ) . '" data-complete-text="' . __( 'Sent', 'wpgen' ) . '" data-error-text="' . __( 'Error', 'wpgen' ) . '">' . __( 'Send', 'wpgen' ) . '</button>';
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

		$html = $before_form . $html . $after_form;

		$html = apply_filters( 'get_feedback_form', $html );

		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'feedback-handler' );

		return $html;
	}
}

/*// Usage: add feedback form before footer.
add_action( 'after_site_content', 'add_feedback_form_before_footer', 10 );
if ( ! function_exists( 'add_feedback_form_before_footer' ) ) {
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
}*/

/*
// Usage: add popup form after footer.
add_action( 'wp_footer', 'add_popup_form_before_footer', 10 );
if ( ! function_exists( 'add_popup_form_before_footer' ) ) {
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
}*/
