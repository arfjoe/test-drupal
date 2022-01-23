(function (Drupal, drupalSettings) {
  Drupal.behaviors.facebook = {
    attach: function attach(context) {
      if (contextIsRoot(context)) {
     window.fbAsyncInit = function() {
    FB.init({
        appId      : drupalSettings.tacServices.facebook_tac_services.facebook_key,
        status     : true,
        xfbml      : true,
        version    : 'v10.0',

    });
    FB.AppEvents.logPageView();
};
        (tarteaucitron.job = tarteaucitron.job || []).push('facebook');
      }
    }
  };
})(Drupal, drupalSettings);