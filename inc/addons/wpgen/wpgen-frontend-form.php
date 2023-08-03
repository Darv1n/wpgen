<?php
/**
 * WpGen frontend form
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'wpgen_frontend_form' ) ) {

	/**
	 * General wpgen form.
	 */
	function wpgen_frontend_form() {

		if ( is_wpgen_active() ) {

			$saturate_array = array( 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950 );

			// Write default options and overwrite them with the settings.
			$defaults = array(
				'customizer-general-container-width'  => wpgen_options( 'general_container_width' ),
				'customizer-archive-page-columns'     => wpgen_options( 'archive_page_columns' ),
				'customizer-general-menu-position'    => wpgen_options( 'general_menu_position' ),
				'customizer-general-menu-button-type' => wpgen_options( 'general_menu_button_type' ),
				'customizer-general-button-type'      => wpgen_options( 'general_button_type' ),
				'customizer-general-color-scheme'     => wpgen_options( 'general_color_scheme' ),
				'general-link-color'                  => 'primary',
				'general-primary-color'               => get_root_defaults( 'primary-color' ),
				'general-secondary-color'             => get_root_defaults( 'secondary-color' ),
				'general-gray-color'                  => get_root_defaults( 'gray-color' ),
				'elem-bg-saturate'                    => get_explode_part( get_root_defaults( 'elemBgSaturate' ), 1, '-' ),
				'elem-bd-color-saturate'              => get_explode_part( get_root_defaults( 'elemBdColor' ), 1, '-' ),
			);

			// Rebuild options via root_options and wpgen_options.
			$root_options = get_option( 'root_options', false );
			if ( $root_options ) {
				$defaults = wp_parse_args( $root_options, $defaults );
			}

			// vardump( $defaults );

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
					'style' => get_root_selected_font(),
				),
				'secondary-font' => array(
					'title' => __( 'Text Font', 'wpgen' ),
					'type'  => 'typography',
					'root'  => 'secondaryFont',
					'style' => get_root_selected_font(),
				),
				'customizer-general-color-scheme' => array(
					'title' => __( 'Color Scheme', 'wpgen' ),
					'type'  => 'customizer',
					'root'  => 'customizerColorScheme',
					'style' => array(
						'white' => 'White',
						'light' => 'Light',
						'dark'  => 'Dark',
						'black' => 'Black',
					),
				),
				'general-primary-color' => array(
					'title'    => __( 'Primary color', 'wpgen' ),
					'type'     => 'color',
					'root'     => 'primary',
					'style'    => array(
						'red'     => 'Red',
						'orange'  => 'Orange',
						'amber'   => 'Amber',
						'yellow'  => 'Yellow',
						'lime'    => 'Lime',
						'green'   => 'Green',
						'emerald' => 'Emerald',
						'teal'    => 'Teal',
						'cyan'    => 'Cyan',
						'sky'     => 'Sky',
						'blue'    => 'Blue',
						'indigo'  => 'Indigo',
						'violet'  => 'Violet',
						'purple'  => 'Purple',
						'fuchsia' => 'Fuchsia',
						'pink'    => 'Pink',
						'rose'    => 'Rose',
					),
				),
				'general-secondary-color' => array(
					'title'    => __( 'Secondary color', 'wpgen' ),
					'type'     => 'color',
					'root'     => 'secondary',
					'style'    => array(
						'red'     => 'Red',
						'orange'  => 'Orange',
						'amber'   => 'Amber',
						'yellow'  => 'Yellow',
						'lime'    => 'Lime',
						'green'   => 'Green',
						'emerald' => 'Emerald',
						'teal'    => 'Teal',
						'cyan'    => 'Cyan',
						'sky'     => 'Sky',
						'blue'    => 'Blue',
						'indigo'  => 'Indigo',
						'violet'  => 'Violet',
						'purple'  => 'Purple',
						'fuchsia' => 'Fuchsia',
						'pink'    => 'Pink',
						'rose'    => 'Rose',
					),
				),
				'general-gray-color' => array(
					'title'    => __( 'Gray color', 'wpgen' ),
					'type'     => 'color',
					'root'     => 'gray',
					'style'    => array(
						'slate'   => 'Slate',
						'gray'    => 'Gray',
						'zinc'    => 'Zinc',
						'neutral' => 'Neutral',
						'stone'   => 'Stone',
					),
				),
				'general-link-color' => array(
					'title'   => __( 'Link color', 'wpgen' ),
					'type'    => 'color',
					'root'    => 'linkColor',
					'style'   => array(
						'primary'   => 'Primary',
						'secondary' => 'Secondary',
						'blue'      => 'Blue',
						'gray'      => 'Gray',
					),
					'default' => 'primary',
				),
				'customizer-general-button-type' => array(
					'title' => __( 'Button type', 'wpgen' ),
					'type'  => 'customizer',
					'root'  => 'customizerButtonType',
					'style' => array(
						'common'   => 'Common',
						'empty'    => 'Empty',
						'gradient' => 'Gradient',
						'slide'    => 'Slide',
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
				'btn-bd-width' => array(
					'title' => __( 'Button border width', 'wpgen' ),
					'type'  => 'form',
					'root'  => 'btnBdWidth',
					'style' => array(
						'border'      => 'border',
						'border-2'    => 'border-2',
						'border-4'    => 'border-4',
						'border-8'    => 'border-8',
					),
				),
				'btn-bd-radius' => array(
					'title' => __( 'Button border radius', 'wpgen' ),
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
				'elem-bg-saturate' => array(
					'title' => __( 'Background of inactive elements', 'wpgen' ),
					'type'  => 'elem',
					'root'  => 'elemBgSaturate',
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
						'950' => '950',
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
						'primary'   => 'primary',
						'secondary' => 'secondary',
						'bg'        => 'gray',
					),
					// 'help'    => __( 'To disable the border-none option in the previous paragraph', 'wpgen' ),
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
						'950' => '950',
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
			);

			$elems = apply_filters( 'wpgen_frontend_form_options', $elems );

		$out = '<button id="wpgen-btn" class="' . esc_attr( implode( ' ', get_button_classes( 'button-wpgen icon icon_pen', 'default' ) ) ) . '" type="button" data-text-on="' . __( 'Close WpGen', 'wpgen' ) . '" data-text-off="' . __( 'Open WpGen', 'wpgen' ) . '" data-opener="off">' . __( 'Open WpGen', 'wpgen' ) . '</button>';
		$out .= '<div id="wpgen-popup" class="wpgen-popup" data-opener="off">';
				$out .= '<form id="wpgen-form" class="form wpgen-form" method="post">';
					$out .= '<fieldset class="button-set">';
						$out .= '<button id="wpgen-random" class="' . esc_attr( implode( ' ', get_button_classes( 'icon icon_shuffle' ) ) ) . '">' . __( 'Random', 'wpgen' ) . '</button>';
						$out .= '<button id="wpgen-save" class="' . esc_attr( implode( ' ', get_button_classes( 'wpgen-action icon icon_download', 'secondary' ) ) ) . '" data-action="save">' . __( 'Save', 'wpgen' ) . '</button>';
						$out .= '<button id="wpgen-reset" class="' . esc_attr( implode( ' ', get_button_classes( 'wpgen-action icon icon_trash', 'default' ) ) ) . '" data-action="reset">' . __( 'Default', 'wpgen' ) . '</button>';
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
									if ( $default === $key_c ) {
										$out .= '<option value="' . $key_c . '" selected>' . $value . '</option>';
									} else {
										$out .= '<option value="' . $key_c . '">' . $value . '</option>';
									}
								}

								$out .= '</select>';
								$out .= '<i class="lock icon icon_lock-on" data-lock="on"></i>';
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
add_action( 'wp_footer_close', 'wpgen_frontend_form', 50 );