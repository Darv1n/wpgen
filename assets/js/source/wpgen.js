jQuery(document).ready(function ($) {

	// Клик по кнопке открытия/закрытия формы.
	$( '#footer' ).on( 'click', '#wpgen-btn', function(e) {

		var popup  = $( '#wpgen-popup' );
		var opener = $(this).attr( 'data-opener' );
		
		if ( opener === 'off' ) {
			var newOpener = 'on';
		} else {
			var newOpener = 'off';
		}

		localStorage.setItem( 'wpgenOpener', newOpener );
		formOpener( newOpener );

		e.preventDefault();
	});

	// Если есть значение в localStorage.
	var wpgenOpener = localStorage.getItem( 'wpgenOpener' );
	if ( null !== wpgenOpener ) {
		formOpener( wpgenOpener );
	}

	// Функция открытия/закрытия формы.
	function formOpener( newOpener ) {

		var newText = $( '#wpgen-btn' ).attr( 'data-text-' + newOpener );

		$( '#wpgen-btn' ).attr( 'data-opener', newOpener ).html( newText );
		$( '#wpgen-popup' ).attr( 'data-opener', newOpener );
	}

	// Сохраняем название проекта в localStorage.
	$( '#wpgen-name' ).change(function() {
		localStorage.setItem( 'wpgenName', $(this).val() );
	});

	// Добавляем название в input из localStorage.
	var wpgenName = localStorage.getItem( 'wpgenName' );
	if ( null !== wpgenName ) {
		$( '#wpgen-name' ).val(wpgenName);
	}

	// Устанавливам основной массив даты селекторов из localStorage.
	// var wpgenData = localStorage.getItem( 'wpgenData' );
	// if ( null !== wpgenData ) {
	// 	formDataSaver( JSON.parse( wpgenData ) );
	// }

	// Указываем цвет кнопок в атрибутах (нужно для корректной работы wpgen)
	$.each([ '.button', '.btn' ], function( index, value ) {
		$( value ).each( function() {

			var btn = $(this);
			var classes = $(this).attr( 'class' );

			$.each([ 'secondary', 'gray' ], function( index, value ) {
				if ( classes.includes( value ) ) {
					btn.attr( 'data-color', value );
				}
			});

			// Аттрибут по умолчанию.
			btn.attr( 'data-type', 'common' );

			$.each([ 'empty', 'gradient', 'slide' ], function( index, value ) {
				if ( classes.includes( value ) ) {
					btn.attr( 'data-type', value );
				}
			});
		});
	});




	// Основная функция.
	function formDataSaver( data ) {

		localStorage.setItem( 'wpgenData', JSON.stringify( data ) );

		// var rootString = $( '#wpgen-root-colors' );
		// console.log( rootString.text() );

		var wpgen_value = ajax_wpgen_obj.value;
		var obj = {};

		$.each(data, function( index, value ) {
			// console.log( value.name );
			// console.log( value.value );

			var selector = $( '#' + value.name );
			// console.log( selector );

			selector.data( 'value', value.value );

			if ( selector.length !== 0 ) {

				var selectedOption = selector.find(':selected');

				var type = selector.data( 'type' );
				// var influence = selector.data( 'influence' );
				var currentValue = selector.data( 'value' );
				var root = selector.data( 'root' );

				// console.log( selectedOption.data('darken') )
				// console.log( type );
				// console.log( wpgen_value[currentValue] );

				// $( '.' + influence ).removeClass( currentValue );
				// $( '.' + influence ).addClass( value.value );

				// Основные цвета.
				if ( type === 'color' ) {

					var colorName = selectedOption.data('color-name');
					var colorSaturate = selectedOption.data('color-saturate');

					// общие 
					obj[root + 'ColorDark']    = wpgen_value[selectedOption.data( 'dark' )];
					obj[root + 'ColorDarken']  = wpgen_value[selectedOption.data( 'darken' )];
					obj[root + 'Color']        = wpgen_value[selectedOption.data( 'color' )];
					obj[root + 'ColorLighten'] = wpgen_value[selectedOption.data( 'lighten' )];
					obj[root + 'ColorLight']   = wpgen_value[selectedOption.data( 'light' )];

					// console.log( root + 'Color' );
					// console.log( selectedOption.data( 'color' ) );

					// Цвет текста.
					if ( $.inArray( value.name, [ 'general-primary-color', 'general-secondary-color' ] ) !== -1 ) {
						var elemColorStyle = getOppositeColorStyleBySaturate( selectedOption.data('color-saturate') );
						if ( elemColorStyle == 'white') {
							obj[root + 'ColorText'] = wpgen_value[$( '#general-bg-color' ).find(':selected').data('white')];
						} else {
							obj[root + 'ColorText'] = wpgen_value[$( '#general-bg-color' ).find(':selected').data('black')];
						}
					}

					// Цвет ссылок
					var linkColorName = $( '#general-link-color' ).find(':selected').val();
					if ( linkColorName == root ) {
						obj['linkColorDark']  = wpgen_value[colorName + '-600'];
						obj['linkColor']      = wpgen_value[colorName + '-500'];
						obj['linkColorLight'] = wpgen_value[colorName + '-400'];
					}

					// console.log( colorName + '-500' );

					// Фоновые цвета.
					if ( value.name == 'general-bg-color' ) {
						obj['bgColorDark']    = wpgen_value[selectedOption.data('bg-dark')];
						obj['bgColorDarken']  = wpgen_value[selectedOption.data('bg-darken')];
						obj['bgColorLighten'] = wpgen_value[selectedOption.data('bg-lighten')];
						obj['bgColorLight']   = wpgen_value[selectedOption.data('bg-light')];
						obj['whiteColor']     = wpgen_value[selectedOption.data('white')];
						obj['textColor']      = wpgen_value[selectedOption.data('black')];

						// Добавляем классы в body.
						var themeStyle = getStyleBySaturate( colorSaturate );

						// Устанавливаем стили для body, header, footer, menu.
						setThemeStyle( themeStyle );

						// Фоновые цвета элементов
						var elemBgSaturate = $( '#elem-bg-saturate' ).find(':selected').val();
						obj['elemBgColor'] = wpgen_value[colorName + '-' + elemBgSaturate];
						$( 'body' ).removeClass( 'theme_elems_white theme_elems_light theme_elems_dark theme_elems_black' ).addClass( 'theme_elems_' + getStyleBySaturate( elemBgSaturate ) );

						// Цвет текста фоновых элементов.
						var elemColorStyle = getOppositeColorStyleBySaturate( elemBgSaturate );
						if ( elemColorStyle == 'white' ) {
							obj['elemTextColor'] = wpgen_value[selectedOption.data('white')];
						} else {
							obj['elemTextColor'] = wpgen_value[selectedOption.data('black')];
						}

						// Цвет бордера элементов.
						var elemBdColorName = $( '#elem-bd-color' ).find(':selected').val();
						var elemBdColorSaturate = $( '#elem-bd-color-saturate' ).find(':selected').val();
						var elemBdColorStyle = getOppositeColorStyleBySaturate( elemBdColorSaturate );

						if ( elemBdColorName !== 'gray' ) {
							elemBdColorName = $( '#general-' + elemBdColorName + '-color' ).find(':selected').data('color-name');
						}

						obj['elemBdColor'] = wpgen_value[elemBdColorName + '-' + elemBdColorSaturate];

						// Цвет ховеров бордера элементов.
						if ( elemBdColorStyle == 'white' ) {
							obj['elemBdColorHover'] = wpgen_value[elemBdColorName + '-' + getPrevSaturate( elemBdColorSaturate )];
						} else {
							obj['elemBdColorHover'] = wpgen_value[elemBdColorName + '-' + getNextSaturate( elemBdColorSaturate )];
						}

						// Цвет бордера инпутов.
						var inputBdColorSaturate = $( '#input-bd-color-saturate' ).find(':selected').val();

						if ( getOppositeColorStyleBySaturate( colorSaturate ) === 'white' ) {
							inputBdColorSaturate = getOppositeSaturate( inputBdColorSaturate );
						}

						if ( colorSaturate == 50 && colorSaturate == inputBdColorSaturate ) {
							inputBdColorSaturate = 100;
						}

						obj['inputBdColor'] = wpgen_value[colorName + '-' + inputBdColorSaturate];
						console.log( wpgen_value[colorName + '-' + inputBdColorSaturate] );
					}

					if ( linkColorName == 'blue' ) {
						obj['linkColorDark'] = wpgen_value['blue-600'];
						obj['linkColor'] = wpgen_value['blue-500'];
						obj['linkColorLight'] = wpgen_value['blue-400'];
					}

				} else if ( type == 'customizer' ) {

					// Ширина контента.
					if ( value.name == 'customizer-general-container-width' ) {
						$( '.container' ).removeClass().addClass( 'container container-' + currentValue );
					}

					// Кол-во колонок.
					if ( value.name == 'customizer-archive-page-columns' ) {

						if ( currentValue == 'three' ) {
							$( '.article-column' ).removeClass().addClass( 'article-column col-12 col-sm-6 col-lg-4 article-column-3' );
						}

						if ( currentValue == 'four' ) {
							$( '.article-column' ).removeClass().addClass( 'article-column col-12 col-sm-6 col-lg-4 col-xl-3 article-column-4' );
						}

						if ( currentValue == 'five' ) {
							$( '.article-column' ).removeClass().addClass( 'article-column col-12 col-sm-6 col-lg-3 col-xl-5th article-column-5' );
						}

						if ( currentValue == 'six' ) {
							$( '.article-column' ).removeClass().addClass( 'article-column col-12 col-sm-6 col-lg-3 col-xl-2 article-column-6' );
						}
					}

					// Тип кнопок.
					if ( value.name == 'customizer-general-button-type' ) {

						$.each(['button', 'btn'], function( index, value ) {
							$( '.' + value ).each( function() {

								var btn = $(this);
								var color = $(this).attr( 'data-color' );
								var type = $(this).attr( 'data-type' );

								if ( color != undefined ) {
									btn.removeClass( value + '-' + color + ' ' + value + '-' + type + ' ' + value + '-' + type + '-' + color ).addClass( value + '-' + color + ' ' + value + '-' + currentValue + ' ' + value + '-' + currentValue + '-' + color );
									// console.log( type );
									// console.log( value + '-' + color + ' ' + value + '-' + type + ' ' + value + '-' + type + '-' + color );
									// console.log( value + '-' + currentValue + ' ' + value + '-' + currentValue + '-' + color );
									// console.log( value + '-' + color + ' ' + value + '-gradient ' + value + '-gradient-' + color + ' ' + value + '-slide ' + value + '-slide-' + color );
									// btn.removeClass( value + '-' + color + ' ' + value + '-gradient ' + value + '-gradient-' + color + ' ' + value + '-slide ' + value + '-slide-' + color ).addClass( value + '-' + currentValue + ' ' + value + '-' + currentValue + '-' + color );
								} else {
									btn.removeClass( value + '-' + type ).addClass( value + '-' + currentValue );
									// console.log( type );
									// console.log( value + '-' + color + ' ' + value + '-' + type + ' ' + value + '-' + type + '-' + color );
									// console.log( value + '-' + currentValue + ' ' + value + '-' + currentValue + '-' + color );
								}

								btn.attr( 'data-type', currentValue );

							});
						});
					}

					// Тип меню.
					if ( value.name == 'customizer-general-menu-position' ) {

						var json = [ 
							{ 'header': 'header_menu_' },
							{ '#main-menu': 'main-menu-' },
						];

						$.each(json, function () {
							$.each(this, function (name, value) {
								$( name ).removeClass( value + 'fixed ' + value + 'absolute' );
								$( name ).addClass( value + currentValue );
							});
						});

					}

					// Кнопка меню.
					if ( value.name == 'customizer-general-menu-button-type' ) {

						$( '.menu-toggle' ).empty();

						if ( currentValue == 'button-icon-text' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle button menu-toggle_button menu-toggle_icon' );
						}

						if ( currentValue == 'button-icon' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle button menu-toggle_button menu-toggle_icon menu-toggle_solo-icon' );
						}

						if ( currentValue == 'button-text' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle button menu-toggle_button' );
						}

						if ( currentValue == 'icon' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle btn-reset menu-toggle_icon menu-toggle_solo-icon' );
						}

						if ( currentValue == 'icon-text' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle btn-reset menu-toggle_icon menu-toggle_text' );
						}

						if ( currentValue == 'text' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle menu-toggle_text' );
						}

						if ( $.inArray( currentValue, ['icon', 'button-icon'] ) === -1 ) {
							$( '.menu-toggle' ).html( 'Меню' ).addClass( 'menu-toggle_icon-left' );
						}

					}

				} else if ( type == 'typography' ) {

					var fontName = $( '#' + value.name ).find(':selected').text();

					$( '#' + value.name + '-css' ).attr( 'href', '//fonts.googleapis.com/css2?family=' + fontName.replace(/ /g, '+') + '%3Awght%40400%3B700&display=swap&ver=1.0.0' );

					obj[root] = '\'' + fontName + '\'';

				} else {

					// Исключаем работу с цветами для разных элементов, потому что эти значения ищутся в предыдущем условии и выбираются среди цветовых опций
					if ( $.inArray( value.name, ['btn-bd-radius', 'elem-bg-saturate', 'elem-bd-color',  'elem-bd-color', 'elem-bd-color-saturate', 'input-bd-color-saturate'] ) === -1 ) {
						obj[root] = wpgen_value[currentValue];
					}

					// Тень ховеров.
					if ( value.name == 'elem-shadow' ) {
						obj['elemShadowHover'] = wpgen_value[currentValue].replace( '0.15', '0.25' );
					}

					// Размер кнопок.
					if ( value.name == 'btn-size' ) {
						obj['buttonPaddingTop'] = wpgen_value[currentValue].split(' ')[0];
						obj['buttonPaddingLeft'] = wpgen_value[currentValue].split(' ')[1];
					}

					if ( value.name == 'btn-bd-radius' ) {
						console.log( wpgen_value[currentValue] );
					}

				}

			} // end if selector.length !== 0

		});

		obj['allertColor']  = '#F9423A';
		obj['warningColor'] = '#F3EA5D';
		obj['acceptColor']  = '#79D97C';

		setRootString( obj );

		// var rootString = '';

		// $.each(obj, function( index, value ) {
		// 	rootString = rootString + '--' + index + ': ' + value + ';';
		// });

		// rootString = ':root {' + rootString + '}';
		// $( '#wpgen-root' ).empty().text( getRootString( obj ) );

		console.log( obj );
	}

	// Функция принимает массив с рут-стилямы, выводит их строкой в теге style#wpgen-root.
	function setRootString( obj ) {

		var rootString = '';

		$.each(obj, function( index, value ) {
			rootString = rootString + '--' + index + ': ' + value + ';';
		});

		rootString = ':root {' + rootString + '}';

		$( '#wpgen-root' ).empty().text( rootString );
	}

	function getStyleBySaturate( saturate ) {
		if ( $.inArray( parseInt(saturate), [800, 900] ) !== -1 ) {
			var style = 'black';
		} else if ( $.inArray( parseInt(saturate), [500, 600, 700] ) !== -1 ) {
			var style = 'dark';
		} else if ( $.inArray( parseInt(saturate), [200, 300, 400] ) !== -1 ) {
			var style = 'light';
		} else {
			var style = 'white';
		}
		return style;
	}

	function getOppositeColorStyleBySaturate( saturate ) {
		if ( $.inArray( parseInt(saturate), [500, 600, 700, 800, 900] ) !== -1 ) {
			var style = 'white';
		} else {
			var style = 'black';
		}
		return style;
	}

	function getNextSaturate( saturate ) {

		if ( parseInt( saturate ) == 50 ) {
			value = parseInt( saturate ) + 50;
		} else if ( parseInt( saturate ) == 900 ) {
			value = parseInt( saturate ) - 100;
		} else {
			value = parseInt( saturate ) + 100;
		}

		return value;
	}

	function getPrevSaturate( saturate ) {

		if ( parseInt( saturate ) == 100 ) {
			value = parseInt( saturate ) - 50;
		} else if ( parseInt( saturate ) == 50 ) {
			value = parseInt( saturate ) + 50;
		} else {
			value = parseInt( saturate ) - 100;
		}

		return value;
	}

	function setThemeStyle( themeStyle ) {

		var json = [ 
			{ 'body': 'theme_' },
			{ 'header': 'header_' },
			{ 'footer': 'footer_' },
			{ '#main-menu': 'main-menu-' },
		];
		$.each(json, function () {
			$.each(this, function (name, value) {
				$( name ).removeClass( value + 'white ' + value + 'light ' + value + 'dark ' + value + 'black' );
				$( name ).addClass( value + themeStyle );
			});
		});
	}

	function getOppositeSaturate( saturate ) {

		var obj = {
			'50': '900',
			'100': '800',
			'200': '700',
			'300': '600',
			'400': '500',
			'500': '400',
			'600': '300',
			'700': '200',
			'800': '100',
			'900': '50',
		};

		return obj[saturate];
	}

	// Проверяем установленные замки.
	var wpgenLocks = localStorage.getItem( 'wpgenLocks' );

	if ( null == wpgenLocks ) {
		wpgenLocks = {};
	} else {
		wpgenLocks = JSON.parse( wpgenLocks );

		$.each(wpgenLocks, function( index, value ) {

			if ( value === 'off' ) {
				var oldValue = 'on';
			} else {
				var oldValue = 'off';
			}

			$( '#' + index ).next().attr( 'data-lock', value ).removeClass( 'icon_lock-' + oldValue ).addClass( 'icon_lock-' + value );
		});
	}

	// Клик замка.
	$( '#wpgen-form' ).on( 'click', '.lock', function(e) {

		var id = $(this).prev().attr('id');
		var value = $(this).attr( 'data-lock' );

		if ( value === 'off' ) {
			var newValue = 'on';
		} else {
			var newValue = 'off';
		}

		$(this).attr( 'data-lock', newValue ).removeClass( 'icon_lock-' + value ).addClass( 'icon_lock-' + newValue );

		wpgenLocks[id] = newValue;
		localStorage.setItem( 'wpgenLocks', JSON.stringify( wpgenLocks ) );

		e.preventDefault();
	});

	// Смена селектора.
	$( '.selector' ).change( function(e) { 

		var form = $(e.target).closest( '#wpgen-form' );

		formDataSaver( form.serializeArray() );
	});

	// Клик рандома.
	$( '#footer' ).on( 'click', '#wpgen-random', function(e) {

		$( '#wpgen-form .selector' ).each(function () {
			var options = $(this).children( 'option' );
			var random  = Math.floor( options.length * (Math.random() % 1 ) );
			var lock    = $(this).next().attr( 'data-lock' );

			if ( lock === 'on' ) {
				options.attr( 'selected', false ).eq(random).attr( 'selected', true );
			}
		});

		var form = $(e.target).closest( '#wpgen-form' );

		formDataSaver( form.serializeArray() );
		console.log( form.serializeArray() );

		e.preventDefault();
	});

	// Клик сохранения и сброса данных.
	$( '#footer' ).on( 'click', '.wpgen-action', function(e) {

		var form = $(e.target).closest( '#wpgen-form' );
		var type = $(e.target).attr( 'data-action' );

		if ( type === 'reset' ) {
			$( '.selector' ).each(function () {
				var sDefault = $(this).attr( 'data-default' );
				// console.log( sDefault );
				$(this).val( sDefault );
			});
		}

		if ( ! form.hasClass( 'submited' ) ) {
			$.ajax({
				type: 'POST',
				url: ajax_wpgen_obj.url,
				data: {
					'action': 'wpgen_form_action', // Событие к которому будем обращаться.
					'content': form.serialize(), // Передаём значения формы.
					'type': type, // Передаём атрибут формы.
					'security': ajax_wpgen_obj.nonce, // Используем nonce для защиты.
				},
				beforeSend: function () {
					// console.log( 'beforeSend' );
					form.addClass( 'submited' );
				},
				complete: function () {
					// console.log( 'complete' );
					form.removeClass( 'submited' );
				},
				success: function (response) {
					// console.log( 'success' );
					// console.log( response.data );
					if ( response.success && type === 'reset' ) {
						console.log( form.serializeArray() );
						formDataSaver( form.serializeArray() );
					} else {

					}
				},
				error: function (jqXHR, textStatus, error) {
					if( textStatus == 500 ) {
						console.log( 'Error while adding comment' );
					} else if( textStatus == 'timeout' ) {
						console.log( 'Error: Server doesn\'t respond.' );
					} else {
						console.log( jqXHR );
					}
				}
			})
		}

		e.preventDefault();
	});
});