(function (Drupal) {
	Drupal.behaviors.tacServiceVimeoEmbeb = {
		attach: function attach(context) {
			if (contextIsRoot(context)) {
				(tarteaucitron.job = tarteaucitron.job || []).push("vimeo");
			}
		},
	};
})(Drupal);
