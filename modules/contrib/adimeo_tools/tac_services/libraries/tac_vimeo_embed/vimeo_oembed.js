(function (Drupal) {
	// closure
	"use strict";
	Drupal.behaviors.vimeo_oembed = {
		// behaviors
		attach: function () {
			// We wait for tarteaucitron to be loaded before evaluating cookies
			TacHelpers.addListenerMulti(document,['vimeo_added','vimeo_loaded'],function (e){
				// Check if the cookie is accepted or not
				let isCookieAccepted = TacHelpers.checkCookie("vimeo");
				if (isCookieAccepted || e.type === 'vimeo_loaded') {
					// Select only placehoders whose provider is vimeo and doesn't have "js-validated" class
					let tacPlaceholders = document.querySelectorAll(
						'.tac-media-oembed-placeholder[data-oembed-provider="vimeo"]:not(.js-validated)'
					);
					//ajouter foreach sans if cookieAccepted
					tacPlaceholders.forEach(function (tacPlaceholder) {
						let mediaId = tacPlaceholder.dataset.mediaId;
						let fieldName = tacPlaceholder.dataset.fieldName;

						//Replace the placeholders by the OEmbed iframes if the vimeo
						// service
						// is accepted
						let url =
							"/ajax/tarteaucitron/display/oembed/" +
							mediaId +
							"/" +
							fieldName;
						let ajaxObject = Drupal.ajax({ url: url });
						ajaxObject.execute();
						tacPlaceholder.classList.add('js-validated');
						tacPlaceholder.classList.remove('js-declined');
					});

				}
				else{
					// Select only placehoders whose provider is vimeo and doesn't have "js-validated" class
					let tacPlaceholders = document.querySelectorAll(
						'.tac-media-oembed-placeholder[data-oembed-provider="vimeo"]:not(.js-declined)'
					);
					tacPlaceholders.forEach(function(tacPlaceholder) {
						// Get noCookie placeholder
						let noCookiePlaceholder = TacHelpers.getPlaceholder(
							Drupal.t(
								"Vos préférences en matière de cookie ne vous permettent pas de consulter cette vidéo vimeo."
							)
						);
						// Replace tacPlaceholder with noCookiePlaceholder
						tacPlaceholder.insertAdjacentHTML(
							"beforeend",
							noCookiePlaceholder
						);
						//tacPlaceholder.remove();
						tacPlaceholder.classList.add('js-declined');
						tacPlaceholder.classList.remove('js-validated');
					});

				}
			});
		},
	};
})(Drupal);
