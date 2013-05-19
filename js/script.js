$(function() {

  $('code').each(function() {
    var classes = this.classList;
    var $me = $(this);

    $.each(classes, function(i, name) {
      var parts = name.match(/^language\-([a-z]*)$/i);

      if (parts) {
        $me.addClass('lang-' + parts[1]);
      }
    });

    $me.addClass('prettyprint');
  });

  prettyPrint.call(document.body);
});