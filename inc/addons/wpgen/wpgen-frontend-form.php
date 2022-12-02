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
						'btn-xs' => __( 'btn-xs', 'wpgen' ),
						'btn-sm' => __( 'btn-sm', 'wpgen' ),
						'btn'    => __( 'btn', 'wpgen' ),
						'btn-md' => __( 'btn-md', 'wpgen' ),
						'btn-lg' => __( 'btn-lg', 'wpgen' ),
						'btn-xl' => __( 'btn-xl', 'wpgen' ),
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
						'50'  => __( '50', 'wpgen' ),
						'100' => __( '100', 'wpgen' ),
						'200' => __( '200', 'wpgen' ),
						'300' => __( '300', 'wpgen' ),
						'400' => __( '400', 'wpgen' ),
						'500' => __( '500', 'wpgen' ),
						'600' => __( '600', 'wpgen' ),
						'700' => __( '700', 'wpgen' ),
						'800' => __( '800', 'wpgen' ),
						'900' => __( '900', 'wpgen' ),
					),
				),
				'elem-padding' => array(
					'title' => __( 'Internal element paddings', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemPadding',
					'style' => array(
						// 'p-none' => __( 'p-none', 'wpgen' ),
						// 'p-1' => __( 'p-1', 'wpgen' ),
						'p-2' => __( 'p-2', 'wpgen' ),
						'p-3' => __( 'p-3', 'wpgen' ),
						'p-4' => __( 'p-4', 'wpgen' ),
						'p-5' => __( 'p-5', 'wpgen' ),
						'p-6' => __( 'p-6', 'wpgen' ),
					),
				),
				'elem-bd-width' => array(
					'title' => __( 'Width of border elements', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemBdWidth',
					'style' => array(
						'border-none' => __( 'border-none', 'wpgen' ),
						'border'      => __( 'border', 'wpgen' ),
						'border-2'    => __( 'border-2', 'wpgen' ),
						'border-4'    => __( 'border-4', 'wpgen' ),
						'border-8'    => __( 'border-8', 'wpgen' ),
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
						'50'  => __( '50', 'wpgen' ),
						'100' => __( '100', 'wpgen' ),
						'200' => __( '200', 'wpgen' ),
						'300' => __( '300', 'wpgen' ),
						'400' => __( '400', 'wpgen' ),
						'500' => __( '500', 'wpgen' ),
						'600' => __( '600', 'wpgen' ),
						'700' => __( '700', 'wpgen' ),
						'800' => __( '800', 'wpgen' ),
						'900' => __( '900', 'wpgen' ),
					),
				),
				'elem-shadow' => array(
					'title' => __( 'Shadow elements', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemShadow',
					'style' => array(
						'shadow-none'  => __( 'shadow-none', 'wpgen' ),
						'shadow-sm'    => __( 'shadow-sm', 'wpgen' ),
						'shadow'       => __( 'shadow', 'wpgen' ),
						'shadow-md'    => __( 'shadow-md', 'wpgen' ),
						'shadow-lg'    => __( 'shadow-lg', 'wpgen' ),
						'shadow-xl'    => __( 'shadow-xl', 'wpgen' ),
						'shadow-2xl'   => __( 'shadow-2xl', 'wpgen' ),
						'shadow-inner' => __( 'shadow-inner', 'wpgen' ),
						'shadow-none'  => __( 'shadow-none', 'wpgen' ),
					),
				),
				'elem-bd-radius' => array(
					'title' => __( 'Elements border radius', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemBdRadius',
					'style' => array(
						'rounded-none' => __( 'rounded-none', 'wpgen' ),
						'rounded-sm'   => __( 'rounded-sm', 'wpgen' ),
						'rounded'      => __( 'rounded', 'wpgen' ),
						'rounded-md'   => __( 'rounded-md', 'wpgen' ),
						'rounded-lg'   => __( 'rounded-lg', 'wpgen' ),
						'rounded-xl'   => __( 'rounded-xl', 'wpgen' ),
						'rounded-2xl'  => __( 'rounded-2xl', 'wpgen' ),
						'rounded-3xl'  => __( 'rounded-3xl', 'wpgen' ),
						'rounded-4xl'  => __( 'rounded-4xl', 'wpgen' ),
					),
				),
				'btn-bd-radius' => array(
					'title' => __( 'Buttons border radius', 'wpgen' ),
					'type'  => 'form',
					'root'  => 'btnBdRadius',
					'style' => array(
						'rounded-none' => __( 'rounded-none', 'wpgen' ),
						'rounded-sm'   => __( 'rounded-sm', 'wpgen' ),
						'rounded'      => __( 'rounded', 'wpgen' ),
						'rounded-md'   => __( 'rounded-md', 'wpgen' ),
						'rounded-lg'   => __( 'rounded-lg', 'wpgen' ),
						'rounded-xl'   => __( 'rounded-xl', 'wpgen' ),
						'rounded-2xl'  => __( 'rounded-2xl', 'wpgen' ),
						'rounded-3xl'  => __( 'rounded-3xl', 'wpgen' ),
						'rounded-4xl'  => __( 'rounded-4xl', 'wpgen' ),
					),
				),
				'input-bd-color-saturate' => array(
					'title' => __( 'Border color saturate of input elements', 'wpgen' ),
					'type'  => 'form',
					'root'  => 'inputBdColorSaturate',
					'style' => array(
						'50'  => __( '50 (900 для темной темы)', 'wpgen' ),
						'100' => __( '100 (800 для темной темы)', 'wpgen' ),
						'200' => __( '200 (700 для темной темы)', 'wpgen' ),
						'300' => __( '300 (600 для темной темы)', 'wpgen' ),
						'400' => __( '400 (500 для темной темы)', 'wpgen' ),
						'500' => __( '500 (400 для темной темы)', 'wpgen' ),
						'600' => __( '600 (300 для темной темы)', 'wpgen' ),
						'700' => __( '700 (200 для темной темы)', 'wpgen' ),
						'800' => __( '800 (100 для темной темы)', 'wpgen' ),
						'900' => __( '900 (50 для темной темы)', 'wpgen' ),
					),
				),
			);

			$elems = apply_filters( 'wpgen_form_options', $elems );

			
			/*
			// Usage:
			add_filter( 'wpgen_form_options','child_theme_wpgen_form_options' );
			function child_theme_wpgen_form_options( $elems ) {

				unset( $elems['customizer-archive-page-columns'] );

				return $elems;

			}*/

		$out = '<button id="wpgen-btn" class="' . implode( ' ', get_button_classes( 'btn-wpgen', 'default' ) ) . '" type="button" data-text-on="Close WpGen" data-text-off="Open WpGen" data-opener="off">Open WpGen</button>';
		$out .= '<div id="wpgen-popup" class="wpgen-popup" data-opener="off">';
				$out .= '<form id="wpgen-form" class="form wpgen-form" method="post">';
					$out .= '<fieldset class="btn-set">';
						// $out .= '<input id="wpgen-name" type="text" name="name" required>';
						$out .= '<input id="wpgen-random" type="button" class="' . implode( ' ', get_button_classes() ) . '" value="Random">';
						$out .= '<input id="wpgen-submit" type="submit" class="' . implode( ' ', get_button_classes( 'wpgen-action', 'secondary' ) ) . '" data-action="save" value="Save">';
						$out .= '<input id="wpgen-submit" type="submit" class="' . implode( ' ', get_button_classes( 'wpgen-action', 'default' ) ) . '" data-action="reset" value="Default">';
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
