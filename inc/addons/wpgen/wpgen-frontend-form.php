<?php
/**
 * WpGen frontend form
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_footer_close', 'wpgen_frontend_form', 50 );
if ( ! function_exists( 'wpgen_frontend_form' ) ) {

	/**
	 * General wpgen form.
	 */
	function wpgen_frontend_form() {

		if ( is_wpgen_active() ) {

			$saturate_array      = array( 50, 100, 200, 300, 400, 500, 600, 700, 800, 900 );
			$saturate_array_mini = array( 400, 500, 600 );

			// Write default options and overwrite them with the settings.
			$defaults = array(
				'customizer-general-container-width'  => wpgen_options( 'general_container_width' ),
				'customizer-archive-page-columns'     => wpgen_options( 'archive_page_columns' ),
				'customizer-general-menu-position'    => wpgen_options( 'general_menu_position' ),
				'customizer-general-menu-button-type' => wpgen_options( 'general_menu_button_type' ),
				'customizer-general-button-type'      => wpgen_options( 'general_button_type' ),
				'general-link-color'                  => 'primary',
				'general-primary-color'               => get_root_defaults( 'primaryColor' ),
				'general-secondary-color'             => get_root_defaults( 'secondaryColor' ),
				'general-bg-color'                    => get_root_defaults( 'grayColor' ),
				'elem-bg-saturate'                    => get_explode_part( get_root_defaults( 'elemBgColor' ), 1, '-' ),
				'elem-bd-color-saturate'              => get_explode_part( get_root_defaults( 'elemBdColor' ), 1, '-' ),
				'input-bd-color-saturate'             => get_explode_part( get_root_defaults( 'inputBdColor' ), 1, '-' ),
			);

			// Rebuild options via root_options and wpgen_options.
			$root_options = get_option( 'root_options', false );
			if ( $root_options ) {
				$defaults = wp_parse_args( $root_options, $defaults );
			}

			$wpgen_options = get_option( 'wpgen_options', false );
			if ( $wpgen_options ) {
				$temp = array();
				foreach ( $wpgen_options as $key => $value ) {
					$key_temp          = 'customizer-' . str_replace( '_', '-', $key );
					$temp[ $key_temp ] = $value;
				}
				$defaults = wp_parse_args( $temp, $defaults );
				unset( $temp );
			}

			// Create default array of elements.
			$elems = array(
				'primary-font' => array(
					'title' => __( 'Primary Font', 'wpgen' ),
					'type'  => 'typography',
					'root'  => 'primaryFont',
					'style' => get_selected_font(),
				),
				'secondary-font' => array(
					'title' => __( 'Text Font', 'wpgen' ),
					'type'  => 'typography',
					'root'  => 'secondaryFont',
					'style' => get_selected_font(),
				),
				'customizer-general-container-width' => array(
					'title' => __( 'Container', 'wpgen' ),
					'type'  => 'customizer',
					'root'  => 'customizerContainer',
					'style' => array(
						'narrow'  => __( 'Narrow', 'wpgen' ),
						'general' => __( 'General', 'wpgen' ),
						'average' => __( 'Average', 'wpgen' ),
						'wide'    => __( 'Wide', 'wpgen' ),
						'fluid'   => __( 'Fluid', 'wpgen' ),
					),
				),
				'customizer-archive-page-columns' => array(
					'title' => __( 'Number of columns', 'wpgen' ),
					'type'  => 'customizer',
					'root'  => 'customizerColumns',
					'style' => array(
						'three' => __( 'Three', 'wpgen' ),
						'four'  => __( 'Four', 'wpgen' ),
						'five'  => __( 'Five', 'wpgen' ),
						'six'   => __( 'Six', 'wpgen' ),
					),
				),
				'customizer-general-menu-position' => array(
					'title' => __( 'Menu type', 'wpgen' ),
					'type'  => 'customizer',
					'root'  => 'customizerMenuPosition',
					'style' => array(
						'absolute' => __( 'Absolute', 'wpgen' ),
						'fixed'    => __( 'Fixed', 'wpgen' ),
					),
				),
				'customizer-general-menu-button-type' => array(
					'title' => __( 'Menu button type', 'wpgen' ),
					'type'  => 'customizer',
					'root'  => 'customizerMenuButtonType',
					'style' => array(
						'button-icon-text' => __( 'Button + Icon + Text', 'wpgen' ),
						'button-icon'      => __( 'Button + Icon', 'wpgen' ),
						'button-text'      => __( 'Button + Text', 'wpgen' ),
						'icon'             => __( 'Icon', 'wpgen' ),
						'icon-text'        => __( 'Icon + Text', 'wpgen' ),
						'text'             => __( 'Text', 'wpgen' ),
					),
				),
				'customizer-general-button-type' => array(
					'title' => __( 'Button type', 'wpgen' ),
					'type'  => 'customizer',
					'root'  => 'customizerButtonType',
					'style' => array(
						'common'   => __( 'Common', 'wpgen' ),
						'empty'    => __( 'Empty', 'wpgen' ),
						'gradient' => __( 'Gradient', 'wpgen' ),
						'slide'    => __( 'Slide', 'wpgen' ),
					),
				),
				'btn-size' => array(
					'title' => __( 'Button size', 'wpgen' ),
					'type'  => 'form',
					'root'  => 'btnSize',
					'style' => array(
						'btn-xs' => 'btn-xs',
						'btn-sm' => 'btn-sm',
						'btn'    => 'btn',
						'btn-md' => 'btn-md',
						'btn-lg' => 'btn-lg',
						'btn-xl' => 'btn-xl',
					),
				),
				'general-link-color' => array(
					'title'   => __( 'Link color', 'wpgen' ),
					'type'    => 'elem',
					'root'    => 'linkColor',
					'style'   => array(
						'primary'   => __( 'primary', 'wpgen' ),
						'secondary' => __( 'secondary', 'wpgen' ),
						'blue'      => __( 'blue', 'wpgen' ),
						'gray'      => __( 'gray', 'wpgen' ),
					),
					'default' => 'primary',
				),
				'general-primary-color' => array(
					'title'    => __( 'Primary color', 'wpgen' ),
					'type'     => 'color',
					'root'     => 'primary',
					'saturate' => 'mini',
					'style'    => array(
						'red'     => __( 'red', 'wpgen' ),
						'orange'  => __( 'orange', 'wpgen' ),
						'amber'   => __( 'amber', 'wpgen' ),
						'yellow'  => __( 'yellow', 'wpgen' ),
						'lime'    => __( 'lime', 'wpgen' ),
						'green'   => __( 'green', 'wpgen' ),
						'emerald' => __( 'emerald', 'wpgen' ),
						'teal'    => __( 'teal', 'wpgen' ),
						'cyan'    => __( 'cyan', 'wpgen' ),
						'sky'     => __( 'sky', 'wpgen' ),
						'blue'    => __( 'blue', 'wpgen' ),
						'indigo'  => __( 'indigo', 'wpgen' ),
						'violet'  => __( 'violet', 'wpgen' ),
						'purple'  => __( 'purple', 'wpgen' ),
						'fuchsia' => __( 'fuchsia', 'wpgen' ),
						'pink'    => __( 'pink', 'wpgen' ),
						'rose'    => __( 'rose', 'wpgen' ),
					),
				),
				'general-secondary-color' => array(
					'title'    => __( 'Secondary color', 'wpgen' ),
					'type'     => 'color',
					'root'     => 'secondary',
					'saturate' => 'mini',
					'style'    => array(
						'red'     => __( 'red', 'wpgen' ),
						'orange'  => __( 'orange', 'wpgen' ),
						'amber'   => __( 'amber', 'wpgen' ),
						'yellow'  => __( 'yellow', 'wpgen' ),
						'lime'    => __( 'lime', 'wpgen' ),
						'green'   => __( 'green', 'wpgen' ),
						'emerald' => __( 'emerald', 'wpgen' ),
						'teal'    => __( 'teal', 'wpgen' ),
						'cyan'    => __( 'cyan', 'wpgen' ),
						'sky'     => __( 'sky', 'wpgen' ),
						'blue'    => __( 'blue', 'wpgen' ),
						'indigo'  => __( 'indigo', 'wpgen' ),
						'violet'  => __( 'violet', 'wpgen' ),
						'purple'  => __( 'purple', 'wpgen' ),
						'fuchsia' => __( 'fuchsia', 'wpgen' ),
						'pink'    => __( 'pink', 'wpgen' ),
						'rose'    => __( 'rose', 'wpgen' ),
					),
				),
				'general-bg-color' => array(
					'title'    => __( 'Color scheme', 'wpgen' ),
					'type'     => 'color',
					'root'     => 'gray',
					'saturate' => 'saturate',
					'style'    => array(
						'slate'   => __( 'slate', 'wpgen' ),
						'gray'    => __( 'gray', 'wpgen' ),
						'zinc'    => __( 'zinc', 'wpgen' ),
						'neutral' => __( 'neutral', 'wpgen' ),
						'stone'   => __( 'stone', 'wpgen' ),
					),
				),
				'elem-bg-saturate' => array(
					'title' => __( 'Background of inactive elements', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemBgColor',
					'style' => array(
						'50'  => '50',
						'100' => '100',
						'200' => '200',
						'300' => '300',
						'400' => '400',
						'500' => '500',
						'600' => '600',
						'700' => '700',
						'800' => '800',
						'900' => '900',
					),
				),
				'elem-padding' => array(
					'title' => __( 'Internal element paddings', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemPadding',
					'style' => array(
						// 'p-none' => 'p-none',
						// 'p-1' => 'p-1',
						'p-2' => 'p-2',
						'p-3' => 'p-3',
						'p-4' => 'p-4',
						'p-5' => 'p-5',
						'p-6' => 'p-6',
					),
				),
				'elem-bd-width' => array(
					'title' => __( 'Width of border elements', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemBdWidth',
					'style' => array(
						'border-none' => 'border-none',
						'border'      => 'border',
						'border-2'    => 'border-2',
						'border-4'    => 'border-4',
						'border-8'    => 'border-8',
					),
				),

				'elem-bd-color' => array(
					'title'   => __( 'Color of border elements', 'wpgen' ),
					'type'    => 'elem',
					'root'    => 'elemBdColor',
					'style'   => array(
						'primary'   => __( 'primary', 'wpgen' ),
						'secondary' => __( 'secondary', 'wpgen' ),
						'bg'        => __( 'gray', 'wpgen' ),
					),
					'help'    => __( 'To disable the border-none option in the previous paragraph', 'wpgen' ),
					'default' => 'primary',
				),
				'elem-bd-color-saturate' => array(
					'title' => __( 'Color saturate of border elements', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemBdColorSaturate',
					'style' => array(
						'50'  => '50',
						'100' => '100',
						'200' => '200',
						'300' => '300',
						'400' => '400',
						'500' => '500',
						'600' => '600',
						'700' => '700',
						'800' => '800',
						'900' => '900',
					),
				),
				'elem-shadow' => array(
					'title' => __( 'Shadow elements', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemShadow',
					'style' => array(
						'shadow-none'  => 'shadow-none',
						'shadow-sm'    => 'shadow-sm',
						'shadow'       => 'shadow',
						'shadow-md'    => 'shadow-md',
						'shadow-lg'    => 'shadow-lg',
						'shadow-xl'    => 'shadow-xl',
						'shadow-2xl'   => 'shadow-2xl',
						'shadow-inner' => 'shadow-inner',
						'shadow-none'  => 'shadow-none',
					),
				),
				'elem-bd-radius' => array(
					'title' => __( 'Elements border radius', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemBdRadius',
					'style' => array(
						'rounded-none' => 'rounded-none',
						'rounded-sm'   => 'rounded-sm',
						'rounded'      => 'rounded',
						'rounded-md'   => 'rounded-md',
						'rounded-lg'   => 'rounded-lg',
						'rounded-xl'   => 'rounded-xl',
						'rounded-2xl'  => 'rounded-2xl',
						'rounded-3xl'  => 'rounded-3xl',
						'rounded-4xl'  => 'rounded-4xl',
					),
				),
				'btn-bd-radius' => array(
					'title' => __( 'Buttons border radius', 'wpgen' ),
					'type'  => 'form',
					'root'  => 'btnBdRadius',
					'style' => array(
						'rounded-none' => 'rounded-none',
						'rounded-sm'   => 'rounded-sm',
						'rounded'      => 'rounded',
						'rounded-md'   => 'rounded-md',
						'rounded-lg'   => 'rounded-lg',
						'rounded-xl'   => 'rounded-xl',
						'rounded-2xl'  => 'rounded-2xl',
						'rounded-3xl'  => 'rounded-3xl',
						'rounded-4xl'  => 'rounded-4xl',
					),
				),
				'input-bd-color-saturate' => array(
					'title' => __( 'Border color saturate of input elements', 'wpgen' ),
					'type'  => 'form',
					'root'  => 'inputBdColorSaturate',
					'style' => array(
						'50'  => __( '50 (900 for a dark theme)', 'wpgen' ),
						'100' => __( '100 (800 for a dark theme)', 'wpgen' ),
						'200' => __( '200 (700 for a dark theme)', 'wpgen' ),
						'300' => __( '300 (600 for a dark theme)', 'wpgen' ),
						'400' => __( '400 (500 for a dark theme)', 'wpgen' ),
						'500' => __( '500 (400 for a dark theme)', 'wpgen' ),
						'600' => __( '600 (300 for a dark theme)', 'wpgen' ),
						'700' => __( '700 (200 for a dark theme)', 'wpgen' ),
						'800' => __( '800 (100 for a dark theme)', 'wpgen' ),
						'900' => __( '900 (50 for a dark theme)', 'wpgen' ),
					),
				),
			);

			$elems = apply_filters( 'wpgen_form_options', $elems );

			/*
			// Usage:
			add_filter( 'wpgen_form_options', 'child_theme_wpgen_form_options' );
			function child_theme_wpgen_form_options( $elems ) {

				unset( $elems['customizer-archive-page-columns'] );

				return $elems;

			}*/

		$out = '<button id="wpgen-btn" class="' . esc_attr( implode( ' ', get_button_classes( 'btn-wpgen', 'default' ) ) ) . '" type="button" data-text-on="Close WpGen" data-text-off="Open WpGen" data-opener="off">Open WpGen</button>';
		$out .= '<div id="wpgen-popup" class="wpgen-popup" data-opener="off">';
				$out .= '<form id="wpgen-form" class="form wpgen-form" method="post">';
					$out .= '<fieldset class="btn-set">';
						// $out .= '<input id="wpgen-name" type="text" name="name" required>';
						$out .= '<input id="wpgen-random" type="button" class="' . esc_attr( implode( ' ', get_button_classes() ) ) . '" value="Random">';
						$out .= '<input id="wpgen-submit" type="submit" class="' . esc_attr( implode( ' ', get_button_classes( 'wpgen-action', 'secondary' ) ) ) . '" data-action="save" value="Save">';
						$out .= '<input id="wpgen-submit" type="submit" class="' . esc_attr( implode( ' ', get_button_classes( 'wpgen-action', 'default' ) ) ) . '" data-action="reset" value="Default">';
					$out .= '</fieldset>';

					foreach ( $elems as $key_e => $elem ) {

						$first_key = array_key_first( $elem['style'] );

						$out .= '<fieldset class="wpgen-fieldset">';
						$out .= '<legend>' . $elem['title'] . '</legend>';

							$out .= '<div class="form-option">';

								if ( ! isset( $defaults[ $key_e ] ) ) {
									$default = get_root_defaults( $elem['root'] );
								} else {
									$default = $defaults[ $key_e ];
								}

								$out .= '<select id="' . $key_e . '" name="' . $key_e . '" class="selector" data-value="' . $first_key . '" data-type="' . $elem['type'] . '" data-root="' . $elem['root'] . '" data-default="' . $default . '">';

								foreach ( $elem['style'] as $key_c => $value ) {

									if ( $elem['type'] === 'color' ) {

										if ( $elem['saturate'] === 'mini' ) {
											$array = $saturate_array_mini;
										} else {
											$array = $saturate_array;
										}

										foreach ( $array as $key => $saturate ) {

											$color_name = $value . '-' . $saturate;
											$rgb_array  = array();

											$rgb_array['color-name']     = $value;
											$rgb_array['color-saturate'] = $saturate;

											$rgb_array['dark']    = $value . '-' . ( (int) $saturate + 200 );
											$rgb_array['darken']  = $value . '-' . ( (int) $saturate + 100 );
											$rgb_array['color']   = $value . '-' . ( (int) $saturate );
											$rgb_array['lighten'] = $value . '-' . ( (int) $saturate - 100 );
											$rgb_array['light']   = $value . '-' . ( (int) $saturate - 200 );

											if ( $key_e === 'general-bg-color' ) {
												if ( (int) $saturate < 500 ) {
													$rgb_array['dark']    = $value . '-700';
													$rgb_array['darken']  = $value . '-600';
													$rgb_array['color']   = $value . '-500';
													$rgb_array['lighten'] = $value . '-400';
													$rgb_array['light']   = $value . '-300';
												} else {
													$rgb_array['dark']    = $value . '-600';
													$rgb_array['darken']  = $value . '-500';
													$rgb_array['color']   = $value . '-400';
													$rgb_array['lighten'] = $value . '-300';
													$rgb_array['light']   = $value . '-200';
												}

												$rgb_array['bg-dark']    = $value . '-800';
												$rgb_array['bg-darken']  = $value . '-900';
												$rgb_array['bg-lighten'] = $value . '-300';
												$rgb_array['bg-light']   = $value . '-400';

												$rgb_array['white'] = $value . '-50';
												$rgb_array['black'] = $value . '-900';

											}

											$color_data_array = array();
											foreach ( $rgb_array as $key_r => $rgb_value ) {
												$color_data_array[] = 'data-' . $key_r . '="' . $rgb_value . '"';
											}

											$color_data_string = implode( ' ', $color_data_array );

											if ( $default === $color_name ) {
												$out .= '<option value="' . $color_name . '" ' . $color_data_string . ' selected>' . $color_name . '</option>';
											} else {
												$out .= '<option value="' . $color_name . '" ' . $color_data_string . '>' . $color_name . '</option>';
											}
										}
									} else {

										if ( $default == $key_c ) {
											$out .= '<option value="' . $key_c . '" selected>' . $value . '</option>';
										} else {
											$out .= '<option value="' . $key_c . '">' . $value . '</option>';
										}
									}
								}

								$out .= '</select>';
								$out .= '<span class="lock lock-off" data-lock="off"></span>';
							$out .= '</div>';
						$out .= '</fieldset>';
					}
				$out .= '</form>';

			$out .= '</div>';

			$out = apply_filters( 'wpgen_form_html', $out );

			echo $out;

		}
	}
}
