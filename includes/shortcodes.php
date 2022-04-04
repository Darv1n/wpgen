<?php
/**
 * Theme shortcodes
 *
 * @package wpgen
 */

// Добавляем шорткод [wpgen-logo]
// Выводит site branding
add_shortcode( 'wpgen-logo', 'get_wpgen_site_branding' );


// Добавляем шорткод [wpgen-copyright year="2016" display="rights" font-size="small"]
// Выводит Ⓒ 2019 wpgen.com
add_shortcode( 'wpgen-copyright', 'wpgen_shortcode_copyright' );
function wpgen_shortcode_copyright( $atts ) {

	$output = '';

	//$home_url = get_home_url();
	//$home_url = preg_replace('/^(http[s]?):\/\//', '', $home_url);

	$home_url_parts = wp_parse_url( get_home_url() );

	// белый список параметров и значения по умолчанию для шорткода
	$atts = shortcode_atts( array(
		'class'		=> '',
		'title'		=> '',
		'year'		=> '',
		'text'		=> $home_url_parts['host'],
		'display'	=> 'true',
		'font-size'	=> 'normal', // small, large
	), $atts );

	$current_year = date('Y');

	if ( $atts['year'] ) {
		$year = esc_html( $atts['year'] ) . '-' . $current_year;
	} else {
		$year = $current_year;
	}

	$classes[] = 'copyright__item';
	if ( $atts['font-size'] != 'normal' ) $classes[] = esc_html( $atts['font-size'] );

	$utm = '?utm_source=' . $home_url_parts['host'];

	if ( is_multisite() ) {
		$network_url_parts = wp_parse_url( network_home_url() );
		$utm = '?utm_source=' . $network_url_parts['host']  . '&utm_medium=' . $home_url_parts['host'];
	}

	$output .= '<div class="copyright">';
		if ( $atts['display'] == 'true' || $atts['display'] == 'created' ) {
			$output .= '<p class="' . implode( ' ', $classes) . '">' . __( 'Created by', 'wpgen' ) . ' <strong><a href="https://zolin.digital/' . $utm . '" class="link copyright__link initialism">Zolin Digital</a></strong></p>';
		}
		if ( $atts['display'] == 'true' || $atts['display'] == 'rights' ) {
			$output .= '<p class="' . implode( ' ', $classes) . '">&#9400; ' . $year . ' ' . __( 'All right reserved', 'wpgen' ) . ' ' . $atts['text'] . '</p>';
		}
	$output .= '</div>';

	return apply_filters( 'wpgen_shortcode_copyright', $output );
}



// шорткод с текущим годом [current-year year="2019"]
add_shortcode( 'current-year', 'wpgen_current_year' );
function wpgen_current_year( $atts ) {

	// определяем белый список атрибутов
	$atts = shortcode_atts( array(
		'year'	=> date('Y'),
	), $atts );

	$output = '<span class="current-year">' . esc_html( $atts['year'] ) . '</span>';

	return apply_filters( 'wpgen_current_year', $output );
}


// шорткод с текущей датой, [current-date format="j F Y" date="28.01.2020" add_days="1"]
add_shortcode( 'current-date', 'wpgen_current_date' );
function wpgen_current_date( $atts ) {

	// определяем белый список атрибутов
	$atts = shortcode_atts( array(
		'format' => 'j F Y',
		'date'	=> date( "d-m-Y" ),
		'add_days' => '0'
	), $atts );

	$output = mysql2date( $atts['format'], date( "Y-m-d", strtotime( $atts['date'] . ' + ' . $atts['add_days'] . ' days' ) ) );

	return apply_filters( 'current_date', $output );
}




// шорткод с текущим годом [privacy-link font-size="small"]
add_shortcode( 'privacy-link', 'wpgen_privacy_link' );
function wpgen_privacy_link( $atts ) {

	// определяем белый список атрибутов
	$atts = shortcode_atts( array(
		'font-size'	=> 'normal', // small, large
		'text' => __( 'Privacy policy', 'wpgen' ),
	), $atts );

	$output = '';

	$classes[] = 'link';
	$classes[] = 'privacy_link';
	if ( $atts['font-size'] != 'normal' ) $classes[] = esc_html( $atts['font-size'] );


	$privacy_policy_url = get_privacy_policy_url();

	if ( empty($privacy_policy_url) && is_multisite() && !is_main_site() ) {
		switch_to_blog( 1 );
		$privacy_policy_url = get_privacy_policy_url();
		restore_current_blog();
	}

	if ( !empty($privacy_policy_url) ) {
		$output .= '<p><a href="' . $privacy_policy_url . '" class="' . implode( ' ', $classes) . '">' . $atts['text'] . '</a></p>';
	}

	return apply_filters( 'wpgen_current_year', $output );

}




// Добавляем шорткод [wpgen-social-list type="links"] [wpgen-social-list]
// Выводит ссылки на соц.сети указанные в опцях консоли
add_shortcode( 'wpgen-social-list', 'wpgen_shortcode_social_list' );
function wpgen_shortcode_social_list( $atts ) {

	$atts = shortcode_atts( array(
		'class'	=> '',
		'type' => 'icons',
	), $atts );

	$classes[] = 'social-list';
	if ( $atts['type'] == 'links' ) {
		$classes[] = 'social-list_links';
	} else {
		$classes[] = 'social-list_icons';
	}
	$classes[] = $atts['class'];

	$output = '<ul class="' . esc_html( implode(' ', $classes) ) . '">';

		if ( wpgen_options( 'other_vkontakte' ) ) {
			$output .= '<li class="social-list__item social-list__item_vk">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_vkontakte' ) ) . '" class="social-list__link" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] == 'links' ) {
						$output .= 'Vkontakte';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/vk.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_facebook' ) ) {
			$output .= '<li class="social-list__item social-list__item_fb">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_facebook' ) ) . '" class="social-list__link" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] == 'links' ) {
						$output .= 'Facebook';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/facebook.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_instagram' ) ) {
			$output .= '<li class="social-list__item social-list__item_ig">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_instagram' ) ) . '" class="social-list__link" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] == 'links' ) {
						$output .= 'Instagram';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/instagram.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_youtube' ) ) {
			$output .= '<li class="social-list__item social-list__item_yt">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_youtube' ) ) . '" class="social-list__link" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] == 'links' ) {
						$output .= 'Youtube';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/youtube.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_twitter' ) ) {
			$output .= '<li class="social-list__item social-list__item_tw">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_twitter' ) ) . '" class="social-list__link" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] == 'links' ) {
						$output .= 'Twitter';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/twitter.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_telegram' ) ) {
			$output .= '<li class="social-list__item social-list__item_tg">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_telegram' ) ) . '" class="social-list__link" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] == 'links' ) {
						$output .= 'Telegram';
					} else {
						$output .= file_get_contents( get_theme_file_uri( '/assets/img/icons/social/telegram-plane.svg' ) );
					}
				$output .= '</a>';
			$output .= '</li>';
		}
		if ( wpgen_options( 'other_linkedin' ) ) {
			$output .= '<li class="social-list__item social-list__item_in">';
				$output .= '<a href="' . esc_url( wpgen_options( 'other_linkedin' ) ) . '" class="social-list__link" target="_blank" rel="noopener noreferrer">';
					if ( $atts['type'] == 'links' ) {
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

// Добавляем шорткод [wpgen-telegram nick="artzolin"]
// Выводит кнопку telegram с указанным номером в опцях консоли
add_shortcode( 'wpgen-telegram', 'wpgen_shortcode_telegram' );
function wpgen_shortcode_telegram( $atts ) {

	$atts = shortcode_atts( array(
		'class' 	=> 'btn btn-telegram',
		'nick'		=> '',
	), $atts );

	$output = '';

	if ( $atts['nick'] ) {
		$output .= '<a class="' . esc_html( $atts['class'] ) . '" href="https://t.me/' . esc_html( $atts['nick'] ) . '">' .  __( 'Write to Telegram', 'wpgen' ) . '</a>';
	} elseif ( $telegram_nick = wpgen_options( 'other_telegram_nick' ) ) {
		$output .= '<a class="' . esc_html( $atts['class'] ) . '" href="https://t.me/' . esc_html( $telegram_nick ) . '">' .  __( 'Write to Telegram', 'wpgen' ) . '</a>';
	}

	return apply_filters( 'wpgen_shortcode_telegram', $output );
}

// Добавляем шорткод [wpgen-whatsapp number="+79500463854"]
// Выводит кнопку whatsapp с указанным номером в опцях консоли
add_shortcode( 'wpgen-whatsapp', 'wpgen_shortcode_whatsapp' );
function wpgen_shortcode_whatsapp( $atts ) {

	$atts = shortcode_atts( array(
		'class'		=> 'btn btn-whatsapp',
		'number'	=> '',
	), $atts );

	$output = '';

	if ( $atts['number'] ) {
		$whatsapp_phone = preg_replace( '/(\D)/', '', $atts['number'] );
		$output .= '<a class="' . esc_html( $atts['class'] ) . '" href="https://api.whatsapp.com/send?phone=' . esc_html( $whatsapp_phone ) . '">' .  __( 'Write to Whatsapp', 'wpgen' ) . '</a>';
	} elseif ( $whatsapp_phone = wpgen_options( 'other_whatsapp_phone' ) ) {
		$whatsapp_phone = preg_replace( '/(\D)/', '', $whatsapp_phone );
		$output .= '<a class="' . esc_html( $atts['class'] ) . '" href="https://api.whatsapp.com/send?phone=' . esc_html( $whatsapp_phone ) . '">' .  __( 'Write to Whatsapp', 'wpgen' ) . '</a>';
	}

	return apply_filters( 'wpgen_shortcode_whatsapp', $output );
}

// Добавляем шорткод [wpgen-viber number="+79500463854"]
// Выводит кнопку viber с указанным номером в опцях консоли
add_shortcode( 'wpgen-viber', 'wpgen_shortcode_viber' );
function wpgen_shortcode_viber( $atts ) {

	$atts = shortcode_atts( array(
		'class'		=> 'btn btn-viber',
		'number'	=> '',
	), $atts );

	$output = '';

	if ( $atts['number'] ) {
		$whatsapp_phone = preg_replace( '/(\D)/', '', $atts['number'] );
		$output .= '<a class="' . esc_html( $atts['class'] ) . '" href="viber://chat?number=%2B' . esc_html( $viber_phone ) . '">' .  __( 'Write to Viber', 'wpgen' ) . '</a>';
	} elseif ( $viber_phone = wpgen_options( 'other_viber_phone' ) ) {
		$viber_phone = preg_replace( '/(\D)/', '', $viber_phone );
		$output .= '<a class="' . esc_html( $atts['class'] ) . '" href="viber://chat?number=%2B' . esc_html( $viber_phone ) . '">' .  __( 'Write to Viber', 'wpgen' ) . '</a>';
	}

	return apply_filters( 'wpgen_shortcode_whatsapp', $output );
}



// Добавляем шорткод [wpgen-contacts-list]
// Выводит контакты указанные в опцях консоли
add_shortcode( 'wpgen-contacts-list', 'wpgen_shortcode_contacts_list' );
function wpgen_shortcode_contacts_list( $atts ) {

	$atts = shortcode_atts( array(
		'class'		=> 'contacts-list list-unstyled',
	), $atts );

	$output = '';

	if ( wpgen_options( 'other_address' ) || wpgen_options( 'other_phone' ) || wpgen_options( 'other_email' )) {

		$output = '<ul class="' . esc_html( $atts['class'] ) . '">';

			if ( wpgen_options( 'other_address' ) ) {
				$output .= '<li class="contacts-list__item contacts-list__item_address">' . esc_html( wpgen_options( 'other_address' ) ) . '</li>';
			}
			if ( wpgen_options( 'other_phone' ) ) {
				$output .= '<li class="contacts-list__item contacts-list__item_phone"><a href="tel:' . preg_replace('/[^0-9]/', '', esc_html( wpgen_options( 'other_phone' ) ) ) . '" class="contacts-list__link link-minor">' . esc_html( wpgen_options( 'other_phone' ) ) . '</a></li>';
			}
			if ( wpgen_options( 'other_email' ) ) {
				$output .= '<li class="contacts-list__item contacts-list__item_email"><a href="mailto:' . esc_html( wpgen_options( 'other_email' ) ) . '" class="contacts-list__link link-minor">' . esc_html( wpgen_options( 'other_email' ) ) . '</a></li>';
			}

		$output .= '</ul>';

	}

	return apply_filters( 'wpgen_shortcode_contacts_list', $output );
}



// Добавляем шорткод [wpgen-address]
// Выводит адрес указанный в опцях консоли
add_shortcode( 'wpgen-address', 'wpgen_shortcode_address' );
function wpgen_shortcode_address( $output = '' ) {

	$output = '';

	if ( wpgen_options( 'other_address' ) ) {
		$output .= '<p class="contacts-list__item contacts-list__item_address">' . esc_html( wpgen_options( 'other_address' ) ) . '</p>';
	}

	return apply_filters( 'wpgen_shortcode_address', $output );
}



// Добавляем шорткод [wpgen-phone]
// Выводит phone указанный в опцях консоли
add_shortcode( 'wpgen-phone', 'wpgen_shortcode_phone' );
function wpgen_shortcode_phone( $output = '' ) {

	$output = '';

	if ( wpgen_options( 'other_phone' ) ) {
		$output .= '<p class="contacts-list__item contacts-list__item_phone"><a href="tel:' . preg_replace('/[^0-9]/', '', esc_html( wpgen_options( 'other_phone' ) ) ) . '" class="contacts-list__link link-minor">' . esc_html( wpgen_options( 'other_phone' ) ) . '</a></p>';
	}

	return apply_filters( 'wpgen_shortcode_phone', $output );
}



// Добавляем шорткод [wpgen-email]
// Выводит email указанный в опцях консоли
add_shortcode( 'wpgen-email', 'wpgen_shortcode_email' );
function wpgen_shortcode_email( $output = '' ) {

	$output = '';

	if ( wpgen_options( 'other_email' ) ) {
		$output .= '<p class="contacts-list__item contacts-list__item_email"><a href="mailto:' . esc_html( wpgen_options( 'other_email' ) ) . '" class="contacts-list__link link-minor">' . esc_html( wpgen_options( 'other_email' ) ) . '</a></p>';
	}

	return apply_filters( 'wpgen_shortcode_email', $output );
}


