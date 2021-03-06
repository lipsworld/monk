(function( $ ) {
	'use strict';

	$( document ).ready( function() {
		$( document ).on( 'change', 'select[name="monk_default_language"]', function() {
			var selected_language = $( this ).val();
			$( 'input[type="checkbox"][name="monk_active_languages[]"]' ).each( function() {
				var current_language = $( this ).val();
				if ( current_language === selected_language ) {
					$( this ).prop({
						'checked': true
					});
					$( this ).parent().addClass( 'option-disabled' );
				} else {
					if ( $( this ).hasClass( 'monk-saved-language' ) ) {
						$( this ).parent().removeClass( 'option-disabled' );
					} else {
						$( this ).parent().removeClass( 'option-disabled' );
					}
				}
			});
		});

		$( '#monk-default-language option[value=""]' ).val( 'en_US' );

		$( document ).on( 'click', 'span.monk-add-translation', function() {
			$( '.monk-post-meta-add-translation' ).slideToggle( 150 );
		});

		$( document ).on( 'click', 'a.monk-cancel-submit-translation', function( e ) {
			$( '.monk-post-meta-add-translation' ).slideUp( 150 );
			e.preventDefault();
		});

		$( document ).on( 'click', 'span.monk-change-language', function() {
			$( '.monk-change-current-language' ).slideToggle( 150 );
		});

		$( document ).on( 'change', '#new-post-language', function() {
			var new_name = $( 'select[name="monk_post_language"] option:selected' ).text();
			$( '#current-language' ).html( new_name );
		});

		$( document ).on( 'click', 'a.monk-cancel-language-change', function( e ) {
			$( '.monk-change-current-language' ).slideUp( 150 );
			$( 'select[name="monk_new_language"] option[value=""]').attr( 'selected', 'selected' );
			e.preventDefault();
		});

		$( document ).on( 'click', '.monk-attach', function( event ) {
			event.preventDefault();
			var form_data = {
				action           : 'monk_add_attachment_translation',
				monk_id          : $( this ).siblings( '.monk-id' ).val(),
				current_post_id  : $( this ).siblings( '.current-post-id' ).val(),
				lang             : $( this ).siblings( '.monk-lang' ).val(),
			};

			$.ajax({
				type: 'POST',
				url: monk.ajax_url,
				data: form_data,
				success: function( response ) {
					window.location.replace( response.data );
				}
			});

			return false;
		});

		var not_submit = true;
		$( document ).on( 'submit', '#monk-general-form', function( event ) {
			if ( not_submit ) {
				event.preventDefault();
				not_submit = false;
				$( '#monk-spinner' ).addClass( 'is-active' );
				$( '#monk-downloading' ).removeClass( 'monk-hide' );
				var form_data = $( '#monk-general-form' ).serializeArray();
				form_data.push({
					name : 'action',
					value : 'monk_save_general_form_settings',
				});

				$.ajax({
					type: 'POST',
					url: monk.ajax_url,
					data: form_data,
					success: function( response ) {
						if ( response.hasOwnProperty( 'success' ) ) {
							if ( response.success ) {
								if ( response.data ) {
									$( '#monk-downloading' ).addClass( 'monk-hide' );	
									if ( -1 < $.inArray( false, response.data ) ) {		
										$( '#monk-error' ).removeClass( 'monk-hide' );
									} else {		
										$( '#monk-submit-settings' ).click();
									}
								}
							} else {
								$( '#monk-error' ).removeClass( 'monk-hide' );
							}
						}
						setTimeout( function() {
							$( '#monk-spinner' ).removeClass( 'is-active' );
						}, 3000 );
					}
				});
			}
		});

		$( document ).on( 'submit', '#monk-tools-form', function( event ) {
			event.preventDefault();
			if ( $( '#monk-set-language-to-elements' ).prop( 'checked' ) ) {
				$( '#monk-spinner' ).addClass( 'is-active' );
				$( '#monk-bulk-action' ).removeClass( 'monk-hide' );
				var form_data = $( '#monk-tools-form' ).serializeArray();

				$.ajax({
					type: 'POST',
					url: monk.ajax_url,
					data: form_data,
					success: function( response ) {
						if ( response.hasOwnProperty( 'success' ) ) {
							$( '#monk-bulk-action' ).addClass( 'monk-hide' );
							if ( response.success ) {		
								$( '#monk-done' ).removeClass( 'monk-hide' );
							} else {
								$( '#monk-error' ).removeClass( 'monk-hide' );
							}
						}

						setTimeout( function() {
							$( '#monk-error' ).addClass( 'monk-hide' );
							$( '#monk-done' ).addClass( 'monk-hide' );
							$( '#monk-spinner' ).removeClass( 'is-active' );
						}, 2000 );
					}
				});
			} else {
				$( '#monk-checkbox-not-selected-message' ).removeClass( 'monk-hide' );
				setTimeout( function() {
					$( '#monk-checkbox-not-selected-message' ).addClass( 'monk-hide' );
				}, 2000 );
			}
		});

		$( document ).on( 'submit', '#monk-options-form', function( event ) {
			event.preventDefault();
			$( '#monk-spinner' ).addClass( 'is-active' );
				$( '#monk-save-options' ).removeClass( 'monk-hide' );
				var form_data = $( '#monk-options-form' ).serializeArray();

				$.ajax({
					type: 'POST',
					url: monk.ajax_url,
					data: form_data,
					success: function( response ) {
						if ( response.hasOwnProperty( 'success' ) ) {
							$( '#monk-save-options' ).addClass( 'monk-hide' );
							if ( response.success ) {		
								$( '#monk-done' ).removeClass( 'monk-hide' );
							} else {
								$( '#monk-error' ).removeClass( 'monk-hide' );
							}
						}

						setTimeout( function() {
							$( '#monk-error' ).addClass( 'monk-hide' );
							$( '#monk-done' ).addClass( 'monk-hide' );
							$( '#monk-spinner' ).removeClass( 'is-active' );
						}, 2000 );
					}
				});
		});

		$( document ).on( 'click', 'button.monk-change-post-language', function( e ) {
			e.preventDefault();
			$( '.monk-change-current-language' ).slideUp( 150 );
		});

		$( document ).on( 'click', 'button.monk-submit-translation', function( e ) {
			e.preventDefault();
			var encoded_url = $( 'select[name="monk_post_translation_id"]' ).val();
			window.location.replace( encoded_url );
		});

		$( document ).on( 'change', '#monk-term-translation', function( e ) {
			e.preventDefault();
			var encoded_url = $( 'select[name="monk-term-translation"]' ).val();
			window.location.replace( encoded_url );
		});

		var monk_id = $( '#monk-id' ).val();
		var path    = window.location.pathname.split( '/' );
		var url     = window.location.href.split( '&' );

		if ( 'edit-tags.php' === path[ path.length - 1 ] && monk_id ) {
			$( document ).ajaxComplete( function() {
				$( location ).attr( 'href', url[0] );
			});
		};

		/**
		 * Insert language for new attachments added through media modal.
		 *
		 * Hook into uploader success callback getting the uploaded attachment ID and trigger
		 * a change event on its language hidden field to dispatch an AJAX request provided by core
		 * to update attachment language.
		 */
		if ( typeof wp.Uploader !== 'undefined' ) {
			$.extend( wp.Uploader.prototype, {
				success : function( file_attachment ){
					var attachment_id = file_attachment.attributes.id;
					var item          = $( '.attachments li.save-ready' ).not( '.uploading, .saved-language, [data-id]' );
					
					if ( typeof undefined === typeof item.attr( 'data-id' ) ) {
						item.find( '.attachment-preview' ).click();
						item.addClass( 'saved-language' );
					}

					$( '[name="attachments[' + attachment_id + '][language]"]' ).change();
				}
			});
		}

		/**
		 * Replace components from the admin footer to the major-publishing-actions div, in the menu page
		*/
		if ( /\bnav-menus.php?\b/.test( window.location.pathname ) ) {
			if ( $( '.add-menu-translation' ).length ) {
				$( 'div#nav-menu-header .major-publishing-actions' ).append( $( '.add-menu-translation' ) );
				$( $( 'fieldset.menu-language' ) ).insertBefore( 'fieldset.auto-add-pages' );
				$( $( '.menu-translations' ) ).insertAfter( '.menu-settings' );

				if ( $( '#monk-menu-translation-message' ).length ) {
					$( '.menu-theme-locations' ).append( $( '#monk-menu-translation-message' ) );
					$( '.menu-theme-locations  > div.checkbox-input' ).remove();
				}
				if ( $( '#monk-checkbox-wrapper' ).length ) {
					var locations = $( '#monk-menu-locations' ).val().split( '/' );

					for ( var i = 0; i < locations.length - 1; i++) {
						$( '#monk-' + locations[ i ] ).val( $( '#' + locations[ i ] ).val() );
					}
					$( '.menu-theme-locations' ).append( $( '#monk-checkbox-wrapper' ) );
					$( '.menu-theme-locations  > div.checkbox-input' ).remove();
				}
			} else {
				$( 'div#nav-menu-header .major-publishing-actions' ).append( $( '.new-menu-language' ) );
			}

			if ( $( '#monk-select-menu-to-edit-groups' ).length ) {
				$( '#select-menu-to-edit' ).children().remove();
				$( '#select-menu-to-edit' ).append( $( '#monk-select-menu-to-edit-groups' ).children() );
				$( '#monk-select-menu-to-edit-groups' ).remove();
			}

			if ( $( '#monk-menu-locations' ).length ) {
				var locations = $( '#monk-menu-locations' ).val().split( '/' );

				for ( var i = 0; i < locations.length - 1; i++) {
					$( '#monk-' + locations[ i ] ).insertBefore( '#' + locations[ i ] );
					$( '#monk-' + locations[ i ] ).attr( 'name', $( '#' + locations[ i ] ).attr( 'name' ) );
					$( '#' + locations[ i ] ).remove();
					$( '#monk-' + locations[ i ] ).attr( 'id', locations[ i ] );
				}

				$( '#monk-menu-locations' ).remove();
			}
		}

		if ( /\bedit-tags.php?\b/.test( window.location.pathname ) ) {
			var action_url = '';
			var filter_value = '';
			$( '.monk-language-filter-elements' ).insertAfter( '.tablenav div:first-child' );

			$( document ).on( 'change', '#monk-language-filter', function() {
				filter_value = $( 'select[name="monk_language_filter"] option:selected' ).val();
			});

			$( document ).on( 'click', 'input#term-query-submit', function( e ) {
				e.preventDefault();
				action_url  = $( 'input[type="hidden"][name="hidden_action_url"]' ).val();
				action_url += "=";
				action_url += filter_value;
				window.location.replace( action_url );
			});
		}
	});
})( jQuery );
