(function (Drupal, CKEDITOR) {

  CKEDITOR.plugins.add( 'tacoembeddisplay', {
    afterInit: function( editor ) {
      var loadMediaOEmbed = function (editor) {
        var document = editor.document;
        var tacPlaceholders = document.$.querySelectorAll('.tac-media-oembed-placeholder:not(.js-validated)');
        for(var i = 0; i < tacPlaceholders.length; i++) {
          //tacPlaceholder = jQuery(tacPlaceholder);
          var mediaId = tacPlaceholders[i].dataset.mediaId;
          var fieldName = tacPlaceholders[i].dataset.fieldName;
          // var mediaId = tacPlaceholder.data('mediaId');
          // var fieldName = tacPlaceholder.data('fieldName');

          //Replace the placeholders by the OEmbed iframes
          var url = "ajax/tarteaucitron/ckeditor/oembed/" + mediaId + "/" + fieldName;

          var httpRequest = new XMLHttpRequest();
          httpRequest.responseType = 'document';
          httpRequest.onreadystatechange = function (html) {
            tacPlaceholders[i].html(html);
            
            tacPlaceholders[i].classList.add('js-validated');
            tacPlaceholders[i].classList.remove('js-declined');
          }
          httpRequest.open('GET', Drupal.url(url))
          httpRequest.send();
          // jQuery.get({
          //   url: Drupal.url(url),
          //   dataType: 'html',
          // }).done(function(html) {
          //   tacPlaceholder.html(html);
          //   tacPlaceholder.addClass('js-validated');
          //   tacPlaceholder.removeClass('js-declined');
          // });
        }
        // jQuery.each(tacPlaceholders,function(index,tacPlaceholder) {
          
        // });
      };

      editor.on( 'contentDom', function() {
        setTimeout(function () {
          loadMediaOEmbed(editor);
        },3000);
      });
      editor.on( 'change', function() {loadMediaOEmbed(editor);});
      editor.on( 'insertHtml', function() {
        setTimeout(function () {
          loadMediaOEmbed(editor);
        },500);
      });
    }
  });


})(Drupal, CKEDITOR);
