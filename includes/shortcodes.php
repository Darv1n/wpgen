<?php
/**
 * Theme shortcodes
 *
 * @package wpgen
 */

add_shortcode( 'wpgen-logo', 'get_wpgen_site_branding' ); // [wpgen-logo].
add_shortcode( 'wpgen-copyright', 'wpgen_shortcode_copyright' ); // [wpgen-copyright year="2016" display="rights" text="Zolin Digital" link="https://zolin.digital/" font-size="small"].
add_shortcode( 'current-year', 'wpgen_current_year' ); // [current-year year="2019"].
add_shortcode( 'current-date', 'wpgen_current_date' ); // [current-date format="j F Y" date="28.01.2020" add_days="1"].
add_shortcode( 'privacy-link', 'wpgen_privacy_link' ); // [privacy-link font-size="small"].
add_shortcode( 'wpgen-social-list', 'wpgen_shortcode_social_list' ); // [wpgen-social-list type="links"].
add_shortcode( 'wpgen-telegram', 'wpgen_shortcode_telegram' ); // [wpgen-telegram nick="artzolin"].
add_shortcode( 'wpgen-whatsapp', 'wpgen_shortcode_whatsapp' ); // [wpgen-whatsapp number="+79500463854"].
add_shortcode( 'wpgen-viber', 'wpgen_shortcode_viber' ); // [wpgen-viber number="+79500463854"].
add_shortcode( 'wpgen-contacts-list', 'wpgen_shortcode_contact_list' ); // [wpgen-contacts-list].
add_shortcode( 'wpgen-address', 'wpgen_shortcode_address' ); // [wpgen-address].
add_shortcode( 'wpgen-phone', 'wpgen_shortcode_phone' ); // [wpgen-phone].
add_shortcode( 'wpgen-email', 'wpgen_shortcode_email' ); // [wpgen-email].


if ( ! function_exists( 'wpgen_shortcode_copyright' ) ) {
	/**
	 * Add shortcode [wpgen-copyright year="2016" display="rights" font-size="small"]
	 *
	 * @param array $atts shortcode attributes..
	 *
	 * @return string
	 */
	function wpgen_shortcode_copyright( $atts ) {

		$output         = '';
		$home_url_parts = wp_parse_url( get_home_url() );

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class'      => '',
			'year'       => '',
			'text'       => 'Zolin Digital',
			'link'       => 'https://zolin.digital/',
			'link-class' => '',
			'display'    => '',
			'font-size'  => 'normal', // small, large.
		), $atts );

		// Собираем utm.
		if ( is_multisite() ) {
			$network_url_parts = wp_parse_url( network_home_url() );
			$utm = add_query_arg( array( 'utm_source' => $home_url_parts['host'], 'utm_medium' => $network_url_parts['host'] ), $atts['link'] );
		} else {
			$utm = add_query_arg( array( 'utm_source' => $home_url_parts['host'] ), $atts['link'] );
		}

		// Собираем год.
		$current_year = gmdate( 'Y' );
		if ( $atts['year'] ) {
			$year = $atts['year'] . '-' . $current_year;
		} else {
			$year = $current_year;
		}

		// Собираем классы для тега <p>.
		$classes[] = 'copyright__item';
		if ( $atts['font-size'] !== 'normal' ) {
			$classes[] = $atts['font-size'];
		}
		$classes = array_map( 'esc_attr', $classes );

		// Собираем классы для тега <a>.
		$links_classes[] = 'link link-color-unborder copyright__link initialism';
		if ( $atts['link-class'] ) {
			$links_classes[] = $atts['link-class'];
		}
		$links_classes = array_map( 'esc_attr', $links_classes );

		// Собираем HTML.
		$output .= '<div class="copyright">';
		if ( empty( $atts['display'] ) || $atts['display'] === 'created' ) {
			$output .= '<p class="' . implode( ' ', $classes ) . '">' . esc_html__( 'Created by', 'wpgen' ) . ' <strong><a href="' . esc_url( $utm ) . '" class="' . implode( ' ', $links_classes ) . '">' . mb_convert_case( esc_html( $atts['text'] ), MB_CASE_TITLE, 'UTF-8' ) . '</a></strong></p>';
		}
		if ( empty( $atts['display'] ) || $atts['display'] === 'rights' ) {
			$output .= '<p class="' . implode( ' ', $classes ) . '">&#9400; ' . esc_html( $year ) . ' ' . esc_html__( 'All rights reserved', 'wpgen' ) . ' ' . esc_html( $home_url_parts['host'] ) . '</p>';
		}
		$output .= '</div>';

		return apply_filters( 'wpgen_shortcode_copyright', $output );
	}
}



if ( ! function_exists( 'wpgen_current_year' ) ) {

	/**
	 * Add shortcode with current year [current-year year="2019"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_current_year( $atts ) {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'year' => gmdate( 'Y' ),
		), $atts );

		$output = '<span class="current-year">' . esc_html( $atts['year'] ) . '</span>';

		return apply_filters( 'wpgen_current_year', $output );
	}
}



if ( ! function_exists( 'wpgen_current_date' ) ) {

	/**
	 * Add shortcode with current date [current-date format="j F Y" date="28.01.2020" add_days="1"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_current_date( $atts ) {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'format'   => 'j F Y',
			'date'     => gmdate( 'd-m-Y' ),
			'add_days' => '0',
		), $atts );

		$output = mysql2date( $atts['format'], gmdate( 'Y-m-d', strtotime( $atts['date'] . ' + ' . $atts['add_days'] . ' days' ) ) );

		return apply_filters( 'current_date', $output );
	}
}



if ( ! function_exists( 'wpgen_privacy_link' ) ) {

	/**
	 * Add shortcode with privacy link [privacy-link font-size="small"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_privacy_link( $atts ) {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class'     => '',
			'text'      => esc_html__( 'Privacy policy', 'wpgen' ),
			'font-size'	=> 'normal', // small, large.
		), $atts );

		$output = '';

		// Собираем классы ссылки.
		$classes[] = 'link';
		$classes[] = 'link-color-unborder';
		$classes[] = 'privacy_link';
		if ( $atts['font-size'] !== 'normal' ) {
			$classes[] = $atts['font-size'];
		}
		if ( $atts['class'] ) {
			$classes[] = $atts['class'];
		}
		$classes = array_map( 'esc_attr', $classes );

		// Собираем ссылку.
		if ( is_multisite() && ! is_main_site() ) {
			switch_to_blog( 1 );
			$privacy_policy_url = get_privacy_policy_url();
			restore_current_blog();
		} else {
			$privacy_policy_url = get_privacy_policy_url();
		}

		if ( ! empty( $privacy_policy_url ) ) {
			$output .= '<p><a href="' . esc_url( $privacy_policy_url ) . '" class="' . implode( ' ', $classes ) . '">' . esc_html( $atts['text'] ) . '</a></p>';
		}

		return apply_filters( 'wpgen_current_year', $output );
	}
}



if ( ! function_exists( 'wpgen_shortcode_social_list' ) ) {

	/**
	 * Add shortcode with social list [wpgen-social-list type="links"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_shortcode_social_list( $atts ) {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class'	=> '',
			'type'  => 'icons',
		), $atts );

		// Собираем классы списка.
		$classes[] = 'social-list';
		$classes[] = 'social-list_' . $atts['class'];
		if ( $atts['class'] ) {
			$classes[] = $atts['class'];
		}
		$classes = array_map( 'esc_attr', $classes );

		// Собираем HTML.
		$output = '<ul class="' . esc_attr( implode( ' ', $classes ) ) . '">';
		if ( wpgen_options( 'other_vkontakte' ) ) {
			$output .= '<li class="social-list__item social-list__item_vk">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_vkontakte' ) ) . '" class="social-list__link link link-color-unborder" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] === 'links' ) {
						$output .= 'Vkontakte';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/vk.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_facebook' ) ) {
			$output .= '<li class="social-list__item social-list__item_fb">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_facebook' ) ) . '" class="social-list__link link link-color-unborder" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] === 'links' ) {
						$output .= 'Facebook';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/facebook.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_instagram' ) ) {
			$output .= '<li class="social-list__item social-list__item_ig">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_instagram' ) ) . '" class="social-list__link link link-color-unborder" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] === 'links' ) {
						$output .= 'Instagram';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/instagram.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_youtube' ) ) {
			$output .= '<li class="social-list__item social-list__item_yt">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_youtube' ) ) . '" class="social-list__link link link-color-unborder" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] === 'links' ) {
						$output .= 'Youtube';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/youtube.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_twitter' ) ) {
			$output .= '<li class="social-list__item social-list__item_tw">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_twitter' ) ) . '" class="social-list__link link link-color-unborder" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] === 'links' ) {
						$output .= 'Twitter';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/twitter.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_telegram' ) ) {
			$output .= '<li class="social-list__item social-list__item_tg">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_telegram' ) ) . '" class="social-list__link link link-color-unborder" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] === 'links' ) {
						$output .= 'Telegram';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/telegram-plane.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_linkedin' ) ) {
			$output .= '<li class="social-list__item social-list__item_in">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_linkedin' ) ) . '" class="social-list__link link link-color-unborder" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] === 'links' ) {
						$output .= 'LinkedIn';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/linkedin.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		$output .= '</ul>';

		return apply_filters( 'wpgen_shortcode_social_list', $output );
	}
}




if ( ! function_exists( 'wpgen_shortcode_telegram' ) ) {

	/**
	 * Add shortcode with telegram link [wpgen-telegram nick="artzolin"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_shortcode_telegram( $atts ) {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class' => 'btn btn-telegram',
			'nick'  => wpgen_options( 'other_telegram_nick' ),
		), $atts );

		$output = '<a class="' . esc_attr( $atts['class'] ) . '" href="' . esc_url( 'https://t.me/' . esc_html( $atts['nick'] ) ) . '">' . esc_html__( 'Write to Telegram', 'wpgen' ) . '</a>';

		return apply_filters( 'wpgen_shortcode_telegram', $output );
	}
}



if ( ! function_exists( 'wpgen_shortcode_whatsapp' ) ) {

	/**
	 * Add shortcode with whatsapp link [wpgen-whatsapp number="+79500463854"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_shortcode_whatsapp( $atts ) {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class'  => 'btn btn-whatsapp',
			'number' => wpgen_options( 'other_whatsapp_phone' ),
		), $atts );

		$whatsapp_phone = preg_replace( '/(\D)/', '', $atts['number'] );
		$output .= '<a class="' . esc_attr( $atts['class'] ) . '" href="https://api.whatsapp.com/send?phone=' . esc_html( $whatsapp_phone ) . '">' . esc_html__( 'Write to Whatsapp', 'wpgen' ) . '</a>';

		return apply_filters( 'wpgen_shortcode_whatsapp', $output );
	}
}



if ( ! function_exists( 'wpgen_shortcode_viber' ) ) {

	/**
	 * Add shortcode with viber link [wpgen-viber number="+79500463854"]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_shortcode_viber( $atts ) {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class'  => 'btn btn-viber',
			'number' => wpgen_options( 'other_viber_phone' ),
		), $atts );

		$viber_phone = preg_replace( '/(\D)/', '', $atts['number'] );
		$output = '<a class="' . esc_attr( $atts['class'] ) . '" href="viber://chat?number=%2B' . esc_html( $viber_phone ) . '">' . esc_html__( 'Write to Viber', 'wpgen' ) . '</a>';

		return apply_filters( 'wpgen_shortcode_whatsapp', $output );
	}
}



if ( ! function_exists( 'wpgen_shortcode_contact_list' ) ) {

	/**
	 * Add shortcode with contact list [wpgen-contacts-list]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_shortcode_contact_list( $atts ) {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class' => 'contacts-list list-unstyled',
		), $atts );

		$output = '';

		if ( wpgen_options( 'other_address' ) || wpgen_options( 'other_phone' ) || wpgen_options( 'other_email' ) ) {

			$output = '<ul class="' . esc_attr( $atts['class'] ) . '">';
			if ( wpgen_options( 'other_address' ) ) {
				$output .= '<li class="contacts-list__item contacts-list__item_address">' . esc_html( wpgen_options( 'other_address' ) ) . '</li>';
			}
			if ( wpgen_options( 'other_phone' ) ) {
				$output .= '<li class="contacts-list__item contacts-list__item_phone"><a href="tel:' . esc_html( preg_replace( '/[^0-9]/', '', wpgen_options( 'other_phone' ) ) ) . '" class="contacts-list__link link link-color-unborder">' . esc_html( wpgen_options( 'other_phone' ) ) . '</a></li>';
			}
			if ( wpgen_options( 'other_email' ) ) {
				$output .= '<li class="contacts-list__item contacts-list__item_email"><a href="mailto:' . esc_html( wpgen_options( 'other_email' ) ) . '" class="contacts-list__link link link-color-unborder">' . esc_html( wpgen_options( 'other_email' ) ) . '</a></li>';
			}
			$output .= '</ul>';

		}

		return apply_filters( 'wpgen_shortcode_contact_list', $output );
	}
}



if ( ! function_exists( 'wpgen_shortcode_address' ) ) {

	/**
	 * Add shortcode with address [wpgen-address]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_shortcode_address() {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class' => 'contacts-list__item contacts-list__item_address',
			'text'  => wpgen_options( 'other_address' ),
		), $atts );

		$output = '<p class="' . esc_attr( $atts['class'] ) . '">' . esc_html( $atts['text'] ) . '</p>';

		return apply_filters( 'wpgen_shortcode_address', $output );
	}
}



if ( ! function_exists( 'wpgen_shortcode_phone' ) ) {
	/**
	 * Add shortcode with phone number [wpgen-phone]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_shortcode_phone() {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class' => 'contacts-list__item contacts-list__item_phone',
			'phone' => wpgen_options( 'other_phone' ),
		), $atts );

		$output = '<p class="' . esc_attr( $atts['class'] ) . '"><a href="tel:' . esc_html( preg_replace( '/[^0-9]/', '', $atts['phone'] ) ) . '" class="contacts-list__link link link-color-unborder">' . esc_html( $atts['phone'] ) . '</a></p>';

		return apply_filters( 'wpgen_shortcode_phone', $output );
	}
}



if ( ! function_exists( 'wpgen_shortcode_email' ) ) {

	/**
	 * Add shortcode with email [wpgen-email]
	 *
	 * @param array $atts shortcode attributes.
	 *
	 * @return string
	 */
	function wpgen_shortcode_email() {

		// Define a white list of attributes.
		$atts = shortcode_atts( array(
			'class' => 'contacts-list__item contacts-list__item_email',
			'email' => wpgen_options( 'other_email' ),
		), $atts );

		$output = '<p class="' . esc_attr( $atts['class'] ) . '"><a href="mailto:' . esc_html( $atts['email'] ) . '" class="contacts-list__link link link-color-unborder">' . esc_html( $atts['email'] ) . '</a></p>';

		return apply_filters( 'wpgen_shortcode_email', $output );
	}
}
