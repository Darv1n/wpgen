/**
 * Frontend contact form handler
 *
 * Setup js scripts - /inc/setup.php
 * Form handler     - /inc/addons/init-form.php
 */

jQuery(document).ready(function ($) {

	// When popup button is clicked, we set its name to the form (to know from which form the message came)
	$( '.popup-button' ).click( function () {
		var data  = $(this).data( 'name' );
		$( '#form-label' ).val( data );
	});

	var form = $( '#feedback-form' );

	// Form Submitting.
	form.on( 'click', '#form-submit', function (e) {

		e.preventDefault();
		var _this = $( e.target );

		// Ajax request.
		if ( ! form.hasClass( 'submited' ) ) {
			$.ajax({
				type: 'POST',
				url: feedback_handler_obj.url, // ajax url (set in /inc/setup.php).
				data: {
					'action': 'feedback_form_action', // php event handler (/inc/addons/init-form.php).
					'content': form.serialize(), // form values.
					// 'security': feedback_handler_obj.nonce, // ajax nonce (set in /inc/setup.php).
				},
				beforeSend: function () {
					_this.data( 'process-text' );
					form.find( '.error' ).removeClass( 'error' );
					form.find( '.notification' ).remove();
					form.addClass( 'submited' );
				},
				complete: function () {
					_this.data( 'complete-text' );
					form.trigger( 'reset' ).removeClass( 'submited' );
				},
				success: function ( response ) {
					console.log( response.data );
					if ( response.success ) {
						form.after( '<div class="notification notification_accept transition">' + response.data + '</div>' );
						if ( $(".mfp-ready").length > 0 ) {
							setTimeout( function () {
								$.magnificPopup.close();
							}, 2500 );
						}
					} else {
						_this.data( 'error-text' );
						$.each( response.data, function ( key, val ) {
							form.find( '#' + key ).addClass( 'error' );
							form.find( '#' + key ).after( '<span class="notification notification_warning notification_warning_' + key + ' transition">' + val + '</span>' );
						});
					}
				},
				error: function ( jqXHR, textStatus, error ) {
					_this.data( 'error-text' );
				}
			})
		}
	});
});