(function (Drupal, drupalSettings) {
    Drupal.behaviors.tacServiceGAJS = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {
                tarteaucitron.user.gajsUa = drupalSettings.tacServices.google_analytics_old_tac_service.google_ga_old_key;
                tarteaucitron.user.gajsMore = function () { /* add here your optionnal ga.push() */
                };
                (tarteaucitron.job = tarteaucitron.job || []).push('gajs');
            }
        }
    };
})(Drupal, drupalSettings);