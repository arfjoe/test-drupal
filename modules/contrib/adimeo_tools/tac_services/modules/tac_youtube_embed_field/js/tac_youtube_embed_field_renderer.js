(function (Drupal) {
    // closure
    "use strict";

    function initYoutubeIframe(placeholder) {
        let width = placeholder.dataset.width;
        let height = placeholder.dataset.height;
        let frameborder = placeholder.dataset.frameborder;
        let allowfullscreen = placeholder.dataset.allowfullscreen;
        let src = placeholder.dataset.src

        let iframe = document.createElement('iframe');
        iframe.setAttribute('width', width);
        iframe.setAttribute('height', height);
        iframe.setAttribute('frameborder', frameborder);
        iframe.setAttribute('allowfullscreen', allowfullscreen);
        iframe.setAttribute('src', src);
        return iframe;
    }

    Drupal.behaviors.tac_youtube_field_renderer = {

        attach: function () {

            // We wait for tarteaucitron to be loaded before evaluating cookies
            TacHelpers.addListenerMulti(document, ['youtube_added', 'youtube_loaded'], function (e) {
                // Check if the cookie is accepted or not
                let isCookieAccepted = TacHelpers.checkCookie("youtube");
                if (isCookieAccepted || e.type === 'youtube_loaded') {
                    // Select only placehoders whose provider is Youtube and doesn't have "js-validated" class
                    let tacPlaceholders = document.querySelectorAll(
                        '.tac-youtube-embed-field[data-provider="youtube"]:not(.js-validated)'
                    );
                    tacPlaceholders.forEach(function (tacPlaceholder) {

                      //Creating the iframe
                        let iframe = initYoutubeIframe(tacPlaceholder);

                        // Add specific class
                        tacPlaceholder.classList.add('js-validated');

                        // Insert into the dom
                        let parent = tacPlaceholder.parentNode;
                        tacPlaceholder.parentNode.insertBefore(
                            iframe, tacPlaceholder.nextSibling
                        );

                    });

                } else {
                    // Select only placehoders whose provider is Youtube and doesn't have "js-validated" class
                    let tacPlaceholders = document.querySelectorAll(
                        '.tac-youtube-embed-field[data-provider="youtube"]:not(.js-declined)'
                    );
                    tacPlaceholders.forEach(function (tacPlaceholder) {
                        // Get noCookie placeholder
                        let noCookiePlaceholder = TacHelpers.getPlaceholder(
                            Drupal.t(
                                "Vos préférences en matière de cookie ne vous permettent pas de consulter cette vidéo Youtube."
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

