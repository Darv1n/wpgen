jQuery(document).ready(function ($) {

	// При клике устанавливаем кнопку, с которой идет вызов popup.
	$( '.popup-button' ).click( function () {
		var data  = $(this).data( 'name' );
		$( '#form-label' ).val(data);
	});

	var form = $( '.form' );

	// Отправка формы.
	form.on( 'click', '.form-submit', function (e) {

		e.preventDefault();
		var _this = $( e.target );

		// Отправляем запрос Ajax в WordPress.
		if ( ! form.hasClass( 'submited' ) ) {
			$.ajax({
				type: 'POST',
				url: form_obj.url, // Путь к файлу admin-ajax.php
				data: {
					'action': 'form_action', // Событие к которому будем обращаться.
					'content': form.serialize(), // Передаём значения формы.
					// 'security': form_obj.nonce, // Используем nonce для защиты.
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
					console.log( 'success' );
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
							$( '.form-' + key ).addClass( 'error' );
							$( '.form-' + key ).after( '<span class="notification notification_warning notification_warning_' + key + ' transition">' + val + '</span>' );
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