/**
 * Frontend wpgen form handler.
 *
 * Setup js scripts          - /inc/setup.php
 * Form handler              - /inc/addons/wpgen/wpgen-ajax-handler.php
 * Frontend form             - /inc/addons/wpgen/wpgen-frontend-form.php
 * Filter customizer options - /inc/addons/wpgen/wpgen-customizer.php
 * Filter root options       - /inc/addons/wpgen/wpgen-root-styles.php
 */

jQuery(document).ready(function ($) {

	// Click button to open/close the wpgen form.
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

	// If isset on/off value in localStorage.
	var wpgenOpener = localStorage.getItem( 'wpgenOpener' );
	if ( wpgenOpener !== null ) {
		formOpener( wpgenOpener );
	}

	// Open/close form function.
	function formOpener( newOpener ) {

		var newText = $( '#wpgen-btn' ).attr( 'data-text-' + newOpener );

		$( '#wpgen-btn' ).attr( 'data-opener', newOpener ).html( newText );
		$( '#wpgen-popup' ).attr( 'data-opener', newOpener );
	}

	// Save project name in localStorage.
	$( '#wpgen-name' ).change(function() {
		localStorage.setItem( 'wpgenName', $(this).val() );
	});

	// Add title to input from localStorage.
	var wpgenName = localStorage.getItem( 'wpgenName' );
	if ( wpgenName !== null ) {
		$( '#wpgen-name' ).val(wpgenName);
	}

	// Specifies the color of the buttons in the attributes (necessary for the correct operation of pwgen)
	$.each([ '.button', '.btn' ], function( index, value ) {
		$( value ).each( function() {

			var btn     = $(this);
			var classes = btn.attr( 'class' );

			$.each([ 'primary', 'secondary', 'gray', 'default' ], function( index, value ) {
				if ( classes.includes( value ) ) {
					btn.attr( 'data-color', value );
				}
			});

			// Default attribute.
			btn.attr( 'data-type', 'common' );

			$.each([ 'empty', 'gradient', 'slide' ], function( index, value ) {
				if ( classes.includes( value ) ) {
					btn.attr( 'data-type', value );
				}
			});
		});
	});

	// Main function.
	function formDataSaver( data ) {

		localStorage.setItem( 'wpgenData', JSON.stringify( data ) );

		var wpgen_value = ajax_wpgen_obj.value;
		var obj = {};

		$.each( data, function( index, value ) {

			var selector = $( '#' + value.name );

			selector.data( 'value', value.value );

			if ( selector.length !== 0 ) {

				var selectedOption = selector.find(':selected');
					type           = selector.data( 'type' );
					currentValue   = selector.data( 'value' );
					root           = selector.data( 'root' );

				// Primary colors.
				if ( type === 'color' ) {

					if ( root === 'linkColor' ) {

						// Link colors.
						if ( currentValue === 'blue' ) {
							obj['linkColorDark']  = wpgen_value['blue-600'];
							obj['linkColor']      = wpgen_value['blue-500'];
							obj['linkColorLight'] = wpgen_value['blue-400'];
						} else {
							var linkColorName = $( '#general-' + currentValue + '-color' ).find(':selected').val();

							obj['linkColorDark']  = wpgen_value[ linkColorName + '-600' ];
							obj['linkColor']      = wpgen_value[ linkColorName + '-500' ];
							obj['linkColorLight'] = wpgen_value[ linkColorName + '-400' ];
						}
					} else {

						// General values.
						$.each( [ 50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950 ], function( i, v ) {
							obj[ root + '-color-' + v ]  = wpgen_value[ currentValue + '-' + v ];
						});
					}

					if ( root === 'gray' ) {
						var colorSheme = $( '#customizer-general-color-scheme' ).find(':selected').val();

						// White color.
						obj['whiteColor'] = wpgen_value[ currentValue + '-50' ];

						// Background and text color depending on the color scheme.
						if ( colorSheme === 'black' ) {
							obj['primary-bg-color']         = wpgen_value[ currentValue + '-950' ];
							obj['primary-bg-color-hover']   = wpgen_value[ currentValue + '-900' ];
							obj['primary-bd-color']         = wpgen_value[ currentValue + '-800' ];
							obj['primary-bd-color-hover']   = wpgen_value[ currentValue + '-700' ];
							obj['primary-gray-color']       = wpgen_value[ currentValue + '-300' ];
							obj['primary-gray-color-hover'] = wpgen_value[ currentValue + '-400' ];
							obj['primary-text-color']       = wpgen_value[ currentValue + '-50' ];
							obj['svg-filter']               = 'invert(100%)';
						} else if ( colorSheme === 'dark' ) {
							obj['primary-bg-color']         = wpgen_value[ currentValue + '-800' ];
							obj['primary-bg-color-hover']   = wpgen_value[ currentValue + '-900' ];
							obj['primary-bd-color']         = wpgen_value[ currentValue + '-900' ];
							obj['primary-bd-color-hover']   = wpgen_value[ currentValue + '-950' ];
							obj['primary-gray-color']       = wpgen_value[ currentValue + '-300' ];
							obj['primary-gray-color-hover'] = wpgen_value[ currentValue + '-400' ];
							obj['primary-text-color']       = wpgen_value[ currentValue + '-50' ];
							obj['svg-filter']               = 'invert(100%)';
						} else if ( colorSheme === 'light' ) {
							obj['primary-bg-color']         = wpgen_value[ currentValue + '-200' ];
							obj['primary-bg-color-hover']   = wpgen_value[ currentValue + '-300' ];
							obj['primary-bd-color']         = wpgen_value[ currentValue + '-300' ];
							obj['primary-bd-color-hover']   = wpgen_value[ currentValue + '-400' ];
							obj['primary-gray-color']       = wpgen_value[ currentValue + '-500' ];
							obj['primary-gray-color-hover'] = wpgen_value[ currentValue + '-600' ];
							obj['primary-text-color']       = wpgen_value[ currentValue + '-950' ];
							obj['svg-filter']               = 'invert(0%)';
						} else {
							obj['primary-bg-color']         = wpgen_value[ currentValue + '-50' ];
							obj['primary-bg-color-hover']   = wpgen_value[ currentValue + '-200' ];
							obj['primary-bd-color']         = wpgen_value[ currentValue + '-300' ];
							obj['primary-bd-color-hover']   = wpgen_value[ currentValue + '-400' ];
							obj['primary-gray-color']       = wpgen_value[ currentValue + '-500' ];
							obj['primary-gray-color-hover'] = wpgen_value[ currentValue + '-600' ];
							obj['primary-text-color']       = wpgen_value[ currentValue + '-950' ];
							obj['svg-filter']               = 'invert(0%)';
						}
					}

				} else if ( type === 'elem' ) {

					if ( root === 'elemBgSaturate' ) {
						var elemColorName  = $( '#general-gray-color' ).find(':selected').val();

						// Elems background color.
						obj['elemBgColor'] = wpgen_value[ elemColorName + '-' + currentValue ];

						// Elems text color.
						var elemColorStyle = getOppositeColorStyleBySaturate( currentValue );
						if ( elemColorStyle === 'white' ) {
							obj['elemTextColor'] = wpgen_value[ elemColorName + '-50' ];
						} else {
							obj['elemTextColor'] = wpgen_value[ elemColorName + '-900' ];
						}

						$( 'body' ).removeClass( 'theme_elems_white theme_elems_light theme_elems_dark theme_elems_black' ).addClass( 'theme_elems_' + getStyleBySaturate( currentValue ) );
					} else if ( root === 'elemBdColorSaturate' ) {
						var elemBdColorName  = $( '#elem-bd-color' ).find(':selected').val();
							elemBdColorStyle = getOppositeColorStyleBySaturate( currentValue );
							elemColorName    = $( '#general-' + elemBdColorName + '-color' ).find(':selected').val();

							// Elems border color.
							obj['elemBdColor'] = wpgen_value[ elemColorName + '-' + currentValue ];

							// Elems border hover color.
							if ( elemBdColorStyle === 'white' ) {
								obj['elemBdColorHover'] = wpgen_value[ elemColorName + '-' + getPrevSaturate( currentValue ) ];
							} else {
								obj['elemBdColorHover'] = wpgen_value[ elemColorName + '-' + getNextSaturate( currentValue ) ];
							}
					} else if ( root === 'elemBdColor' ) {
						// We do nothing, because we are working with this value above.
					} else {
						// Other values that are converted directly.
						obj[ root ] = wpgen_value[ currentValue ];
					}

					// Elems shadow hover color.
					if ( root === 'elemShadow' ) {
						obj['elemShadowHover'] = wpgen_value[ currentValue ].replace( '0.15', '0.25' );
					}

				} else if ( type === 'form' ) {

					if ( root === 'btnSize' ) {

						// Button size.
						obj['buttonPaddingTop'] = wpgen_value[ currentValue ].split(' ')[0];
						obj['buttonPaddingLeft'] = wpgen_value[ currentValue ].split(' ')[1];
					} else {
						// Other values that are converted directly.
						obj[ root ] = wpgen_value[ currentValue ];
					}
				} else if ( type === 'typography' ) {

					var fontName = $( '#' + value.name ).find(':selected').text();

					$( '#' + value.name + '-css' ).attr( 'href', '//fonts.googleapis.com/css2?family=' + fontName.replace(/ /g, '+') + '%3Awght%40400%3B700&display=swap&ver=1.0.0' );

					obj[ root ] = '\'' + fontName + '\'';

				} else if ( type === 'customizer' ) {

					if ( root === 'customizerColorScheme' ) {

						setThemeStyle( currentValue );

					} else if ( root === 'customizerContainer' ) {

						// Content width.
						$( '.container' ).removeClass().addClass( 'container container-' + currentValue );
					} else if ( root === 'customizerColumns' ) {

						// Columns number.
						if ( currentValue === 'three' ) {
							$( '.article-column' ).removeClass().addClass( 'article-column col-12 col-sm-6 col-lg-4 article-column-3' );
						}
						if ( currentValue === 'four' ) {
							$( '.article-column' ).removeClass().addClass( 'article-column col-12 col-sm-6 col-lg-4 col-xl-3 article-column-4' );
						}
						if ( currentValue === 'five' ) {
							$( '.article-column' ).removeClass().addClass( 'article-column col-12 col-sm-6 col-lg-3 col-xl-5th article-column-5' );
						}
						if ( currentValue === 'six' ) {
							$( '.article-column' ).removeClass().addClass( 'article-column col-12 col-sm-6 col-lg-3 col-xl-2 article-column-6' );
						}
					} else if ( root === 'customizerButtonType' ) {

						// Button type.
						$.each(['button', 'btn'], function( index, value ) {
							$( '.' + value ).each( function() {

								var btn    = $(this);
								    color  = btn.attr( 'data-color' );
								    type   = btn.attr( 'data-type' );
								    scheme = $( '#customizer-general-color-scheme' ).find(':selected').val();

								if ( color !== undefined ) {
									btn.removeClass( value + '-' + color + ' ' + value + '-' + type + ' ' + value + '-' + type + '-' + color ).addClass( value + '-' + color + ' ' + value + '-' + currentValue + ' ' + value + '-' + currentValue + '-' + color );
								} else {
									btn.removeClass( value + '-' + type ).addClass( value + '-' + currentValue );
								}

								if ( btn.hasClass( 'icon' ) ) {
									if ( ( scheme === 'white' || scheme === 'light' ) && ( currentValue === 'empty' || ( currentValue === 'common' && ( color === 'gray' || color === 'default' ) ) ) ) {
										btn.removeClass( 'icon_white icon_black' ).addClass( 'icon_black' );
									} else {
										btn.removeClass( 'icon_white icon_black' ).addClass( 'icon_white' );
									}
								}

								btn.attr( 'data-type', currentValue );
							});
						});
					} else if ( root === 'customizerMenuPosition' ) {

						// Menu type.
						var json = [ 
							{ 'header': 'header_menu_' },
							{ '#main-menu': 'main-menu-' },
						];

						$.each( json, function () {
							$.each( this, function (name, value) {
								$( name ).removeClass( value + 'fixed ' + value + 'absolute' );
								$( name ).addClass( value + currentValue );
							});
						});
					} else if ( root === 'customizerMenuButtonType' ) {

						// Menu button.
						$( '.menu-toggle' ).empty();

						if ( currentValue === 'button-icon-text' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle button menu-toggle_button menu-toggle_icon' );
						}
						if ( currentValue === 'button-icon' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle button menu-toggle_button menu-toggle_icon menu-toggle_solo-icon' );
						}
						if ( currentValue === 'button-text' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle button menu-toggle_button' );
						}
						if ( currentValue === 'icon' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle btn-reset menu-toggle_icon menu-toggle_solo-icon' );
						}
						if ( currentValue === 'icon-text' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle btn-reset menu-toggle_icon menu-toggle_text' );
						}
						if ( currentValue === 'text' ) {
							$( '.menu-toggle' ).removeClass().addClass( 'menu-toggle menu-toggle_text' );
						}
						if ( $.inArray( currentValue, ['icon', 'button-icon'] ) === -1 ) {
							$( '.menu-toggle' ).html( 'Меню' ).addClass( 'menu-toggle_icon-left' );
						}
					}
				} else {
					console.log( root ); // print if there is anything left.
				}

			} // end if selector.length !== 0

		});

		setRootString( obj );

		// console.log( obj );
	}

	// The function takes an array with root styles, outputs them as a string in the style#wpgen-root tag.
	function setRootString( obj ) {

		var rootString = '';

		$.each(obj, function( index, value ) {
			rootString = rootString + '--' + index + ': ' + value + ';';
		});

		rootString = ':root {' + rootString + '}';

		$( '#wpgen-root' ).empty().text( rootString );
	}

	function getStyleBySaturate( saturate ) {
		if ( $.inArray( parseInt( saturate ), [ 800, 900, 950 ] ) !== -1 ) {
			var style = 'black';
		} else if ( $.inArray( parseInt( saturate ), [ 500, 600, 700 ] ) !== -1 ) {
			var style = 'dark';
		} else if ( $.inArray( parseInt( saturate ), [ 200, 300, 400 ] ) !== -1 ) {
			var style = 'light';
		} else {
			var style = 'white';
		}
		return style;
	}

	function getOppositeColorStyleBySaturate( saturate ) {
		if ( $.inArray( parseInt(saturate), [ 500, 600, 700, 800, 900, 950 ] ) !== -1 ) {
			var style = 'white';
		} else {
			var style = 'black';
		}
		return style;
	}

	function getNextSaturate( saturate ) {

		if ( parseInt( saturate ) === 50 || parseInt( saturate ) === 900 ) {
			value = parseInt( saturate ) + 50;
		} else if ( parseInt( saturate ) === 950 ) {
			value = parseInt( saturate ) - 50;
		} else {
			value = parseInt( saturate ) + 100;
		}

		return value;
	}

	function getPrevSaturate( saturate ) {

		if ( parseInt( saturate ) === 100 || parseInt( saturate ) === 950 ) {
			value = parseInt( saturate ) - 50;
		} else if ( parseInt( saturate ) === 50 ) {
			value = parseInt( saturate ) + 50;
		} else {
			value = parseInt( saturate ) - 100;
		}

		return value;
	}

	function setThemeStyle( themeStyle ) {

		var json = [ 
			{ 'body': 'theme_' },
			// { 'header': 'header_' },
			// { 'footer': 'footer_' },
			// { '#main-menu': 'main-menu-' },
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
			'50': '950',
			'100': '900',
			'200': '800',
			'300': '700',
			'400': '600',
			'500': '500',
			'600': '400',
			'700': '300',
			'800': '200',
			'900': '100',
			'950': '50',
		};

		return obj[ saturate ];
	}

	// Checking locks.
	var wpgenLocks = localStorage.getItem( 'wpgenLocks' );

	if ( wpgenLocks === null ) {
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

	// Click lock.
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

	// Selector change.
	$( '.selector' ).change( function(e) { 

		var form = $(e.target).closest( '#wpgen-form' );

		formDataSaver( form.serializeArray() );
	});

	// Click the button "Random".
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

		e.preventDefault();
	});

	// Click "Save" or "Reset" button.
	$( '#footer' ).on( 'click', '.wpgen-action', function(e) {

		var form = $(e.target).closest( '#wpgen-form' );
		var type = $(e.target).attr( 'data-action' );

		if ( type === 'reset' ) {
			$( '.selector' ).each(function () {
				var sDefault = $(this).attr( 'data-default' );
				$(this).val( sDefault );
			});
		}

		if ( ! form.hasClass( 'submited' ) ) {
			$.ajax({
				type: 'POST',
				url: ajax_wpgen_obj.url, // ajax url (set in /inc/setup.php).
				data: {
					'action': 'wpgen_form_action', // php event handler (/inc/addons/wpgen/wpgen-ajax-handler.php).
					'content': form.serialize(), // form values.
					'type': type, // form attribute.
					// 'security': ajax_wpgen_obj.nonce, // ajax nonce (set in /inc/setup.php).
				},
				beforeSend: function () {
					form.addClass( 'submited' );
				},
				complete: function () {
					form.removeClass( 'submited' );
				},
				success: function ( response ) {
					// console.log( response.data );
					if ( response.success && type === 'reset' ) {
						formDataSaver( form.serializeArray() );
					}
				},
				error: function ( jqXHR, textStatus, error ) {
					if( textStatus === 500 ) {
						console.log( 'Error while adding comment' );
					} else if( textStatus === 'timeout' ) {
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
