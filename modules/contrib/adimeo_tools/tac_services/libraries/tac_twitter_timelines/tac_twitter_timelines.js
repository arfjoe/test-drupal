(function (Drupal) {
    Drupal.behaviors.tacServiceTwitterTimelines = {
        attach: function attach(context) {
            if (contextIsRoot(context)) {
                (tarteaucitron.job = tarteaucitron.job || []).push('twittertimeline');
            }
        }
    };
})(Drupal);