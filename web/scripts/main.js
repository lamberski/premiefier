(function ($) {

  var App = {

  /**
   * Initialize features
   */
  init: function () {
    App.createSearchForm();
    // App.getMovie();
  },

  createSearchForm: function () {

  },

  /**
   * Fetch movie data
   */
  getMovie: function () {
    var $form = $('form');

    $form.on('submit', function (event) {
      var $self = $(this);
      var $button = $self.find('input[type="submit"]');

      $button.val($button.data('processing'));

      $.get($form.attr('action'), function() {
        $button.val($button.data('initial'));
      });

      event.preventDefault();
    });
  }

};

$(function () {
  App.init();
});

})(jQuery);
