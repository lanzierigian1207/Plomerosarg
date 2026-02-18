/**
 * Customizer notification system
 */


(function (api) {

	api.sectionConstructor['webique-customizer-notify-section'] = api.Section.extend(
		{

			// No events for this type of section.
			attachEvents: function () {
			},

			// Always make the section active.
			isContextuallyActive: function () {
				return true;
			}
		}
	);

})( wp.customize );

jQuery( document ).ready(
	function () {

		jQuery( '.webique-customizer-notify-dismiss-recommended-action' ).click(
			function () {

				var id = jQuery( this ).attr( 'id' ),
				action = jQuery( this ).attr( 'data-action' );
				jQuery.ajax(
					{
						type: 'GET',
						data: {action: 'webique_customizer_notify_dismiss_action', id: id, todo: action},
						dataType: 'html',
						url: WebiqueCustomizercompanionObject.webique_ajaxurl,
						beforeSend: function () {
							jQuery( '#' + id ).parent().append( '<div id="temp_load" style="text-align:center"><img src="' + WebiqueCustomizercompanionObject.webique_base_path + '/images/spinner-2x.gif" /></div>' );
						},
						success: function (data) {
							var container          = jQuery( '#' + data ).parent().parent();
							var index              = container.next().data( 'index' );
							var recommended_sction = jQuery( '#accordion-section-ti_customizer_notify_recomended_actions' );
							var actions_count      = recommended_sction.find( '.webique-customizer-plugin-notify-actions-count' );
							var section_title      = recommended_sction.find( '.section-title' );
							jQuery( '.webique-customizer-plugin-notify-actions-count .current-index' ).text( index );
							container.slideToggle().remove();
							if (jQuery( '.webique-theme-recomended-actions_container > .epsilon-recommended-actions' ).length === 0) {

								actions_count.remove();

								if (jQuery( '.webique-theme-recomended-actions_container > .epsilon-recommended-plugins' ).length === 0) {
									jQuery( '.control-section-ti-customizer-notify-recomended-actions' ).remove();
								} else {
									section_title.text( section_title.data( 'plugin_text' ) );
								}

							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							console.log( jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown );
						}
					}
				);
			}
		);

jQuery( '.webique-customizer-notify-dismiss-button-recommended-plugin' ).click(
	function () {
		var id = jQuery( this ).attr( 'id' ),
		action = jQuery( this ).attr( 'data-action' );
		jQuery.ajax(
			{
				type: 'GET',
				data: {action: 'ti_customizer_notify_dismiss_recommended_plugins', id: id, todo: action},
				dataType: 'html',
				url: WebiqueCustomizercompanionObject.webique_ajaxurl,
				beforeSend: function () {
					jQuery( '#' + id ).parent().append( '<div id="temp_load" style="text-align:center"><img src="' + WebiqueCustomizercompanionObject.webique_base_path + '/images/spinner-2x.gif" /></div>' );
				},
				success: function (data) {
					var container = jQuery( '#' + data ).parent().parent();
					var index     = container.next().data( 'index' );
					jQuery( '.webique-customizer-plugin-notify-actions-count .current-index' ).text( index );
					container.slideToggle().remove();

					if (jQuery( '.webique-theme-recomended-actions_container > .epsilon-recommended-plugins' ).length === 0) {
						jQuery( '.control-section-ti-customizer-notify-recomended-section' ).remove();
					}
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log( jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown );
				}
			}
		);
	}
);

		// Remove activate button and replace with activation in progress button.
		// Use MutationObserver instead of DOMNodeInserted
			const observer = new MutationObserver(function (mutationsList) {
				mutationsList.forEach(function (mutation) {
					mutation.addedNodes.forEach(function (node) {
						if (node.nodeType === 1) {
							// Bind click handler if .activate-now is added directly
							if (jQuery(node).hasClass('activate-now')) {
								bindActivateHandler(jQuery(node));
							}

							// Bind click handler to any .activate-now buttons inside added subtree
							jQuery(node).find('.activate-now').each(function () {
								bindActivateHandler(jQuery(this));
							});
						}
					});
				});
			});

			// Start observing the body
			observer.observe(document.body, {
				childList: true,
				subtree: true
			});

			// Bind click event handler
			function bindActivateHandler(button) {
				button.off('click.activateNow').on('click.activateNow', function (e) {
					e.preventDefault(); // Prevent default anchor behavior
					handleActivateNow(jQuery(this));
				});
			}

			// Handler function to activate plugin
			function handleActivateNow(activateButton) {
				const url = activateButton.attr('href');
				if (typeof url !== 'undefined') {
					jQuery.ajax({
						beforeSend: function () {
							activateButton.replaceWith(
								'<a class="button updating-message">' + WebiqueCustomizercompanionObject.webique_activating_string + '...</a>'
							);
						},
						async: true,
						type: 'GET',
						url: url,
						success: function () {
							location.reload();
						}
					});
				}
			}

	}
);

					
					
/**
 * Remove activate button and replace with activation in progress button.
 *
 * @package Webique
 */


jQuery( document ).ready(
	function ($) {
		$( 'body' ).on(
			'click', ' .webique-install-plugin ', function () {
				var slug = $( this ).attr( 'data-slug' );

				wp.updates.installPlugin(
					{
						slug: slug
					}
				);
				return false;
			}
		);

		$( '.activate-now' ).on(
			'click', function (e) {
				
				var activateButton = $( this );
				e.preventDefault();
				if ($( activateButton ).length) {
					var url = $( activateButton ).attr( 'href' );

					if (typeof url !== 'undefined') {
						// Request plugin activation.
						$.ajax(
							{
								beforeSend: function () {
									$( activateButton ).replaceWith( '<a class="button updating-message">'+"activating"+'...</a>' );
								},
								async: true,
								type: 'GET',
								url: url,
								success: function () {
									// Reload the page.
									location.reload();
								}
							}
						);
					}
				}
			}
		);
	}
);
