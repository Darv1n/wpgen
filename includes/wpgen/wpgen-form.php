<?php
/**
 * WpGen frontend form
 *
 * @package wpgen
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'wp_footer_close', 'wpgen_frontend_form', 50 );
if ( !function_exists( 'wpgen_frontend_form' ) ) {
	function wpgen_frontend_form() {

		if ( is_wpgen_active() ) {


			$saturate_array = [
				'50' => __( '50', 'wpgen' ),
				'100' => __( '100', 'wpgen' ),
				'200' => __( '200', 'wpgen' ),
				'300' => __( '300', 'wpgen' ),
				'400' => __( '400', 'wpgen' ),
				'500' => __( '500', 'wpgen' ),
				'600' => __( '600', 'wpgen' ),
				'700' => __( '700', 'wpgen' ),
				'800' => __( '800', 'wpgen' ),
				'900' => __( '900', 'wpgen' ),
			];

			$saturate_array_mini = [
				'400' => __( '400', 'wpgen' ),
				'500' => __( '500', 'wpgen' ),
				'600' => __( '600', 'wpgen' ),
			];


			// получаем шрифты по умолчанию
			$elems = [
				'primary-font' => [
					'title' => 'Акцентный шрифт',
					'type' => 'typography',
					'root' => 'primaryFont',
					'style' => get_selected_font(),
				],
				'secondary-font' => [
					'title' => 'Шрифт для текста',
					'type' => 'typography',
					'root' => 'secondaryFont',
					'style' => get_selected_font(),
				],

				'customizer-general-container-width' => [
					'title' => 'Контейнер',
					'type' => 'customizer',
					'root' => 'customizerContainer',
					'style' => [
						'narrow'		=> __( 'Narrow', 'wpgen' ),
						'general'		=> __( 'General', 'wpgen' ),
						'average'		=> __( 'Average', 'wpgen' ),
						'wide'			=> __( 'Wide', 'wpgen' ),
						'fluid'			=> __( 'Fluid', 'wpgen' ),
					],
					'default' => wpgen_options( 'general_container_width' ),
				],
				'customizer-archive-page-columns' => [
					'title' => 'Кол-во колонок',
					'type' => 'customizer',
					'root' => 'customizerColumns',
					'style' => [
						'three'			=> __( 'Three', 'wpgen' ),
						'four'			=> __( 'Four', 'wpgen' ),
						'five'			=> __( 'Five', 'wpgen' ),
						'six'			=> __( 'Six', 'wpgen' ),
					],
					'default' => wpgen_options( 'archive_page_columns' ),
				],

				'customizer-general-menu-position' => [
					'title' => 'Тип меню',
					'type' => 'customizer',
					'root' => 'customizerMenuPosition',
					'style' => [
						'absolute'		=> __( 'Absolute', 'wpgen' ),
						'fixed'			=> __( 'Fixed', 'wpgen' ),
					],
					'default' => wpgen_options( 'general_menu_position' ),
				],

				'customizer-general-menu-button-type' => [
					'title' => 'Кнопка меню',
					'type' => 'customizer',
					'root' => 'customizerMenuButtonType',
					'style' => [
						'button-icon-text'	=> __( 'Button + Icon + Text', 'wpgen' ),
						'button-icon'		=> __( 'Button + Icon', 'wpgen' ),
						'button-text'		=> __( 'Button + Text', 'wpgen' ),
						'icon'				=> __( 'Icon', 'wpgen' ),
						'icon-text'			=> __( 'Icon + Text', 'wpgen' ),
						'text'				=> __( 'Text', 'wpgen' ),
					],
					'default' => wpgen_options( 'general_menu_button_type' ),
				],

				'customizer-general-button-type' => [
					'title' => 'Тип кнопок',
					'type' => 'customizer',
					'root' => 'customizerButtonType',
					'style' => [
						'common'		=> __( 'Common', 'wpgen' ),
						'gradient'		=> __( 'Gradient', 'wpgen' ),
						'slide'			=> __( 'Slide', 'wpgen' ),
					],
					'default' => wpgen_options( 'general_button_type' ),
				],

				'btn-size' => [
					'title' => 'Размер кнопок',
					'type' => 'form',
					'root' => 'btnSize',
					'style' => [
						'btn-xs' => __( 'btn-xs', 'wpgen' ),
						'btn-sm' => __( 'btn-sm', 'wpgen' ),
						'btn' => __( 'btn', 'wpgen' ),
						'btn-md' => __( 'btn-md', 'wpgen' ),
						'btn-lg' => __( 'btn-lg', 'wpgen' ),
						'btn-xl' => __( 'btn-xl', 'wpgen' ),
					],
				],

				'general-link-color' => [
					'title' => 'Цвет ссылок',
					'type' => 'elem',
					'root' => 'linkColor',
					'style' => [
						'primary' => __( 'primary', 'wpgen' ),
						'secondary' => __( 'secondary', 'wpgen' ),
						'blue' => __( 'blue', 'wpgen' ),
						'gray' => __( 'gray', 'wpgen' ),
					],
					'default' => 'primary',
				],




				'general-primary-color' => [
					'title' => 'Основной цвет',
					'type' => 'color',
					'root' => 'primary',
					'saturate' => 'mini',
					'style' => [
						'red' => __( 'red', 'wpgen' ),
						'orange' => __( 'orange', 'wpgen' ),
						'amber' => __( 'amber', 'wpgen' ),
						'yellow' => __( 'yellow', 'wpgen' ),
						'lime' => __( 'lime', 'wpgen' ),
						'green' => __( 'green', 'wpgen' ),
						'emerald' => __( 'emerald', 'wpgen' ),
						'teal' => __( 'teal', 'wpgen' ),
						'cyan' => __( 'cyan', 'wpgen' ),
						'sky' => __( 'sky', 'wpgen' ),
						'blue' => __( 'blue', 'wpgen' ),
						'indigo' => __( 'indigo', 'wpgen' ),
						'violet' => __( 'violet', 'wpgen' ),
						'purple' => __( 'purple', 'wpgen' ),
						'fuchsia' => __( 'fuchsia', 'wpgen' ),
						'pink' => __( 'pink', 'wpgen' ),
						'rose' => __( 'rose', 'wpgen' ),
					],
					'default' => get_root_defaults( 'primaryColor' ),
				],
				'general-secondary-color' => [
					'title' => 'Второстепенный цвет',
					'type' => 'color',
					'root' => 'secondary',
					'saturate' => 'mini',
					'style' => [
						'red' => __( 'red', 'wpgen' ),
						'orange' => __( 'orange', 'wpgen' ),
						'amber' => __( 'amber', 'wpgen' ),
						'yellow' => __( 'yellow', 'wpgen' ),
						'lime' => __( 'lime', 'wpgen' ),
						'green' => __( 'green', 'wpgen' ),
						'emerald' => __( 'emerald', 'wpgen' ),
						'teal' => __( 'teal', 'wpgen' ),
						'cyan' => __( 'cyan', 'wpgen' ),
						'sky' => __( 'sky', 'wpgen' ),
						'blue' => __( 'blue', 'wpgen' ),
						'indigo' => __( 'indigo', 'wpgen' ),
						'violet' => __( 'violet', 'wpgen' ),
						'purple' => __( 'purple', 'wpgen' ),
						'fuchsia' => __( 'fuchsia', 'wpgen' ),
						'pink' => __( 'pink', 'wpgen' ),
						'rose' => __( 'rose', 'wpgen' ),
					],
					'default' => get_root_defaults( 'secondaryColor' ),
				],
				'general-bg-color' => [
					'title' => 'Цветовая схема',
					'type' => 'color',
					'root' => 'gray',
					'saturate' => 'saturate',
					'style' => [
						'slate' => __( 'slate', 'wpgen' ),
						'gray'=> __( 'gray', 'wpgen' ),
						'zinc'=> __( 'zinc', 'wpgen' ),
						'neutral' => __( 'neutral', 'wpgen' ),
						'stone' => __( 'stone', 'wpgen' ),
					],
					'default' => get_root_defaults( 'grayColor' ),
				],

				'elem-bg-saturate' => [
					'title' => 'Фон неактивных элементов',
					'type' => 'elem',
					'root' => 'elemBgColor',
					'style' => [
						'50' => __( '50', 'wpgen' ),
						'100' => __( '100', 'wpgen' ),
						'200' => __( '200', 'wpgen' ),
						'300' => __( '300', 'wpgen' ),
						'400' => __( '400', 'wpgen' ),
						'500' => __( '500', 'wpgen' ),
						'600' => __( '600', 'wpgen' ),
						'700' => __( '700', 'wpgen' ),
						'800' => __( '800', 'wpgen' ),
						'900' => __( '900', 'wpgen' ),
					],
					'default' => get_explode_part( get_root_defaults( 'elemBgColor' ), 1, '-' ),
				],

				'elem-padding' => [
					'title' => 'Внутренний отступ элементов',
					'type' => 'elem',
					'root' => 'elemPadding',
					'style' => [
						//'p-none' => __( 'p-none', 'wpgen' ),
						//'p-1' => __( 'p-1', 'wpgen' ),
						'p-2' => __( 'p-2', 'wpgen' ),
						'p-3' => __( 'p-3', 'wpgen' ),
						'p-4' => __( 'p-4', 'wpgen' ),
						'p-5' => __( 'p-5', 'wpgen' ),
						'p-6' => __( 'p-6', 'wpgen' ),
					],
				],



				'elem-bd-width' => [
					'title' => 'Ширина бордера элементов',
					'type' => 'elem',
					'root' => 'elemBdWidth',
					'style' => [
						'border-none' => __( 'border-none', 'wpgen' ),
						'border' => __( 'border', 'wpgen' ),
						'border-2' => __( 'border-2', 'wpgen' ),
						'border-4' => __( 'border-4', 'wpgen' ),
						'border-8' => __( 'border-8', 'wpgen' ),
					],
				],

				'elem-bd-color' => [
					'title' => 'Цвет бордера элементов',
					'type' => 'elem',
					'root' => 'elemBdColor',
					'style' => [
						'primary' => __( 'primary', 'wpgen' ),
						'secondary' => __( 'secondary', 'wpgen' ),
						'bg' => __( 'gray', 'wpgen' ),
					],
					'help' => 'Чтобы отключить бордер выберите опцию «border-none» в предыдущем пункте',
					'default' => 'primary',
				],

				'elem-bd-color-saturate' => [
					'title' => 'Насыщенность цвета бордера элементов',
					'type' => 'elem',
					'root' => 'elemBdColorSaturate',
					'style' => [
						'50' => __( '50', 'wpgen' ),
						'100' => __( '100', 'wpgen' ),
						'200' => __( '200', 'wpgen' ),
						'300' => __( '300', 'wpgen' ),
						'400' => __( '400', 'wpgen' ),
						'500' => __( '500', 'wpgen' ),
						'600' => __( '600', 'wpgen' ),
						'700' => __( '700', 'wpgen' ),
						'800' => __( '800', 'wpgen' ),
						'900' => __( '900', 'wpgen' ),
					],
					'default' => get_explode_part( get_root_defaults( 'elemBdColor' ), 1, '-' ),
				],
		
				'elem-shadow' => [
					'title' => 'Тени элементов',
					'type' => 'elem',
					'root' => 'elemShadow',
					'style' => [
						'shadow-none' => __( 'shadow-none', 'wpgen' ),
						'shadow-sm' => __( 'shadow-sm', 'wpgen' ),
						'shadow' => __( 'shadow', 'wpgen' ),
						'shadow-md' => __( 'shadow-md', 'wpgen' ),
						'shadow-lg' => __( 'shadow-lg', 'wpgen' ),
						'shadow-xl' => __( 'shadow-xl', 'wpgen' ),
						'shadow-2xl' => __( 'shadow-2xl', 'wpgen' ),
						'shadow-inner' => __( 'shadow-inner', 'wpgen' ),
						'shadow-none' => __( 'shadow-none', 'wpgen' ),
					],
				],

				'elem-bd-radius' => [
					'title' => 'Радиус бордера элементов',
					'type' => 'elem',
					'root' => 'elemBdRadius',
					'style' => [
						'rounded-none' => __( 'rounded-none', 'wpgen' ),
						'rounded-sm' => __( 'rounded-sm', 'wpgen' ),
						'rounded' => __( 'rounded', 'wpgen' ),
						'rounded-md' => __( 'rounded-md', 'wpgen' ),
						'rounded-lg' => __( 'rounded-lg', 'wpgen' ),
						'rounded-xl' => __( 'rounded-xl', 'wpgen' ),
						'rounded-2xl' => __( 'rounded-2xl', 'wpgen' ),
						'rounded-3xl' => __( 'rounded-3xl', 'wpgen' ),
						'rounded-4xl' => __( 'rounded-4xl', 'wpgen' ),
					],
				],

				'btn-bd-radius' => [
					'title' => 'Радиус бордера элементов',
					'type' => 'form',
					'root' => 'btnBdRadius',
					'style' => [
						'rounded-none' => __( 'rounded-none', 'wpgen' ),
						'rounded-sm' => __( 'rounded-sm', 'wpgen' ),
						'rounded' => __( 'rounded', 'wpgen' ),
						'rounded-md' => __( 'rounded-md', 'wpgen' ),
						'rounded-lg' => __( 'rounded-lg', 'wpgen' ),
						'rounded-xl' => __( 'rounded-xl', 'wpgen' ),
						'rounded-2xl' => __( 'rounded-2xl', 'wpgen' ),
						'rounded-3xl' => __( 'rounded-3xl', 'wpgen' ),
						'rounded-4xl' => __( 'rounded-4xl', 'wpgen' ),
					],
				],

				'input-bd-color-saturate' => [
					'title' => 'Насыщенность цвета бордера инпутов',
					'type' => 'form',
					'root' => 'inputBdColorSaturate',
					'style' => [
						'50' => __( '50 (900 для темной темы)', 'wpgen' ),
						'100' => __( '100 (800 для темной темы)', 'wpgen' ),
						'200' => __( '200 (700 для темной темы)', 'wpgen' ),
						'300' => __( '300 (600 для темной темы)', 'wpgen' ),
						'400' => __( '400 (500 для темной темы)', 'wpgen' ),
						'500' => __( '500 (400 для темной темы)', 'wpgen' ),
						'600' => __( '600 (300 для темной темы)', 'wpgen' ),
						'700' => __( '700 (200 для темной темы)', 'wpgen' ),
						'800' => __( '800 (100 для темной темы)', 'wpgen' ),
						'900' => __( '900 (50 для темной темы)', 'wpgen' ),
					],
					'default' => get_explode_part( get_root_defaults( 'inputBdColor' ), 1, '-' ),
				],

			];




		$out = '<button id="wpgen-btn" class="btn btn-default btn-wpgen" type="button" data-text-on="Close WpGen" data-text-off="Open WpGen" data-opener="off">Open WpGen</button>';
		$out .= '<div id="wpgen-popup" class="wpgen-popup" data-opener="off">';
				$out .= '<form id="wpgen-form" class="form wpgen-form" method="post">';
					$out .= '<fieldset class="btn-set">';
						//$out .= '<input id="wpgen-name" type="text" name="name" required>';
						$out .= '<input id="wpgen-random" type="button" class="btn" value="Random">';
						$out .= '<input id="wpgen-submit" type="submit" class="btn wpgen-action btn-secondary" data-action="save" value="Save">';
						$out .= '<input id="wpgen-submit" type="submit" class="btn wpgen-action btn-default" data-action="reset" value="Default">';
					$out .= '</fieldset>';

					foreach ( $elems as $keyE => $elem ) {

						$first_key = array_key_first( $elem['style'] );

						$out .= '<fieldset class="wpgen-fieldset">';
						$out .= '<legend>' . $elem['title'] . '</legend>';

							$out .= '<div class="form-option">';

								if ( !isset( $elem['default'] ) ) {
									$default = get_root_defaults( $elem['root'] );
								} else {
									$default = $elem['default'];
								}

								$out .= '<select id="' . $keyE . '" name="' . $keyE . '" class="selector" data-value="' . $first_key . '" data-type="' . $elem['type'] . '" data-root="' . $elem['root'] . '" data-default="' . $default . '">';
								
								foreach ( $elem['style'] as $keyC => $value ) {

									if ( $elem['type'] === 'color' ) {

										if ( $elem['saturate'] === 'mini' ) {
											$array = $saturate_array_mini;
										} else {
											$array = $saturate_array;
										}

										foreach ( $array as $key => $saturate ) {

											$color_name = $value . '-' . $saturate;
											$rgb_array = array();

											$rgb_array['color-name'] = $value;
											$rgb_array['color-saturate'] = $saturate;

											$rgb_array['dark'] = $value . '-' . ( (int) $saturate + 200 );
											$rgb_array['darken'] = $value . '-' . ( (int) $saturate + 100 );
											$rgb_array['color'] = $value . '-' . ( (int) $saturate );
											$rgb_array['lighten'] = $value . '-' . ( (int) $saturate - 100 );
											$rgb_array['light'] = $value . '-' . ( (int) $saturate - 200 );

											if ( $keyE === 'general-bg-color' ) {
												if ( (int) $saturate < 500 ) {
													$rgb_array['dark'] = $value . '-700';
													$rgb_array['darken'] = $value . '-600';
													$rgb_array['color'] = $value . '-500';
													$rgb_array['lighten'] = $value . '-400';
													$rgb_array['light'] = $value . '-300';
												} else {
													$rgb_array['dark'] = $value . '-600';
													$rgb_array['darken'] = $value . '-500';
													$rgb_array['color'] = $value . '-400';
													$rgb_array['lighten'] = $value . '-300';
													$rgb_array['light'] = $value . '-200';
												}

												$rgb_array['bg-dark'] = $value . '-800';
												$rgb_array['bg-darken'] = $value . '-900';
												$rgb_array['bg-lighten'] = $value . '-300';
												$rgb_array['bg-light'] = $value . '-400';

												$rgb_array['white'] = $value . '-50';
												$rgb_array['black'] = $value . '-900';

											}

											$color_data_array = array();
											foreach ( $rgb_array as $keyR => $rgb_value ) {
												$color_data_array[] = 'data-' . $keyR . '="' . $rgb_value . '"';
											}

											$color_data_string = implode( ' ', $color_data_array );

											$out .= '<option value="' . $color_name . '" ' . $color_data_string . '>' . $color_name . '</option>';

										}
									} else {

										$out .= '<option value="' . $keyC . '">' . $value . '</option>';
									}

								}

								$out .= '</select>';
								$out .= '<span class="lock lock-off" data-lock="off"></span>';
							$out .= '</div>';
						$out .= '</fieldset>';

					}

				$out .= '</form>';

			$out .= '</div>';


			echo $out;


		} // end if is_wpgen_active()
		
	}
}