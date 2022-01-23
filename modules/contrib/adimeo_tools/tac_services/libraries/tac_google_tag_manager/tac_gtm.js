(function (Drupal, DrupalSettings) {
  Drupal.behaviors.tacServiceGTM = {
    attach: function attach(context) {
      if (contextIsRoot(context)) {
        tarteaucitron.user.googletagmanagerId = DrupalSettings.tacServices.google_tag_manager_tac_service.google_tag_manager_key;
        (tarteaucitron.job = tarteaucitron.job || []).push('googletagmanager');
      }
    }
  };
})(Drupal, drupalSettings);