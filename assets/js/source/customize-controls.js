/*
** Scripts within the customizer controls window.
*/

(function( $ ) {
	wp.customize.bind( 'ready', function() {
		
		// Label.
		function wpgen_customizer_title( id, title ) {

			if ( id === 'custom_logo' || id === 'site_icon' || id === 'background_image' ) {
				$( '#customize-control-' + id ).before( '<li class="tab-title customize-control">' + title + '</li>' );
			} else {
				$( '#customize-control-wpgen_options-' + id ).before( '<li class="tab-title customize-control">' + title + '</li>' );
			}
			
		}

		// Checkbox lock section.
 		function wpgen_customizer_checkbox_lock_section( id ) {

			var id = '#customize-control-wpgen_options-' + id;

			$( id ).addClass( 'tab-title' );

			// On change.
			$( id ).find( 'input[type="checkbox"]' ).change(function() {
				if ( $(this).is( ':checked' ) ) {
					$(this).closest( 'li' ).nextUntil( '.tab-title' ).not( '.section-meta, .tab-title' + id ).find( '.control-lock' ).remove();
				} else {
					$(this).closest( 'li' ).nextUntil( '.tab-title' ).not( '.section-meta, .tab-title' + id ).append( '<div class="control-lock"></div>' );
				}
			});

			// On load.
			if ( ! $( id ).find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
				$( id ).closest( 'li' ).nextUntil( '.tab-title' ).not( '.section-meta, .tab-title' + id ).append( '<div class="control-lock"></div>' );
			}

		}

		// Checkbox lock next.
		function wpgen_customizer_checkbox_lock_next( id ) {

			var id = '#customize-control-wpgen_options-' + id;

			// On change.
			$( id ).find( 'input[type="checkbox"]' ).change(function() {
				if ( $(this).is( ':checked' ) ) {
					$(this).closest( 'li' ).next().not( '.section-meta, .tab-title' + id ).append( '<div class="control-lock"></div>' );
				} else {
					$(this).closest( 'li' ).next().not( '.section-meta, .tab-title' + id ).find( '.control-lock' ).remove();
				}
			});

			// On load.
			if ( $( id ).find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
				$( id ).closest( 'li' ).next().not( '.section-meta, .tab-title' + id ).append( '<div class="control-lock"></div>' );
			}

		}

		// Checkbox lock.
 		function wpgen_customizer_checkbox_lock( id, title, count ) {

			var id = '#customize-control-wpgen_options-' + id;
			var cnt = count;
			var sect = title;

			if ( sect === true ) {
				$( id ).addClass( 'customize-control-label' );
			}

			// On change.
			$( id ).find( 'input[type="checkbox"]' ).change(function() {
				if ( $(this).is( ':checked' ) ) {
					$(this).closest( 'li' ).nextAll().slice( 0, + cnt ).find( '.control-lock' ).remove();
				} else {
					$(this).closest( 'li' ).nextAll().slice( 0, + cnt ).append( '<div class="control-lock"></div>' );
				}
			});

			// On load.
			if ( ! $( id ).find( 'input[type="checkbox"]' ).is( ':checked' ) ) {
				$( id ).closest( 'li' ).nextAll().slice( 0, + cnt ).append( '<div class="control-lock"></div>' );
			}

		}

		wpgen_customizer_checkbox_lock( 'general_menu_display', true, 2 );
		wpgen_customizer_checkbox_lock( 'general_breadcrumbs_display', true, 1 );
		wpgen_customizer_checkbox_lock( 'general_scroll_top_display', true, 1 );
		wpgen_customizer_checkbox_lock( 'single_post_meta_display', true, 5 );
		wpgen_customizer_checkbox_lock( 'single_post_entry_footer_display', true, 3 );
		wpgen_customizer_checkbox_lock( 'archive_page_meta_display', true, 7 );
		wpgen_customizer_checkbox_lock( 'archive_page_detail', true, 2 );

/*		wpgen_customizer_checkbox_lock( 'sidebar_display_home', false, 1 );
		wpgen_customizer_checkbox_lock( 'sidebar_display_post', false, 1 );
		wpgen_customizer_checkbox_lock( 'sidebar_display_page', false, 1 );
		wpgen_customizer_checkbox_lock( 'sidebar_display_archive', false, 1 );
		wpgen_customizer_checkbox_lock( 'sidebar_display_search', false, 1 );
		wpgen_customizer_checkbox_lock( 'sidebar_display_error', false, 1 );
		wpgen_customizer_checkbox_lock( 'sidebar_display_author', false, 1 );
*/
/*		wpgen_customizer_title( 'sidebar_display', 'Which pages display sidebar?' );*/

	}); // wp.customize ready
})( jQuery );
