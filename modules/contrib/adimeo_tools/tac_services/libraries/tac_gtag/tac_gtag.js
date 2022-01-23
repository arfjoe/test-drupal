(function (Drupal, drupalSettings) {
    Drupal.behaviors.tacServiceGTag = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {
                tarteaucitron.user.gtagUa = drupalSettings.tacServices.google_gtag_tac_service.google_gtag_key;
                tarteaucitron.user.gtagMore = function () { /* add here your optionnal gtag() */ };
                (tarteaucitron.job = tarteaucitron.job || []).push('gtag');
            }
        }
    };
})(Drupal, drupalSettings);