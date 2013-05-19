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

  $('.doc a').filter(function() {
    return $(this).attr('href').substr(0, 'http'.length) === 'http';
  }).attr('target', '_new');

  var $body = $('body');

  if (typeof localStorage.full === 'undefined') {
    localStorage.full = 'false';
  }

  $('.full_screen').click(function(e) {
    e.preventDefault();

    $body.toggleClass('full-screen');

    localStorage.full = $body.hasClass('full-screen') && 'true' || 'false';
  });


  if (localStorage.full === 'true') {
    $body.addClass('full-screen');
  }

  prettyPrint.call(document.body);
});