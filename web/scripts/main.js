(function ($) {

  var App = {

    elements: {
      body: $('body')
    },

    templates: {
      search    : $('#search-template'),
      movie     : $('#movie-template'),
      subscribe : $('#subscribe-template')
    },

    containers: {
      search    : $('#search-container'),
      movie     : $('#movie-container'),
      subscribe : $('#subscribe-container')
    },

    compileTemplate: function (name, data) {
      var template = Handlebars.compile(App.templates[name].html());
      App.containers[name].html(template(data));
    },

    /**
     * Initialize features
     */
    init: function () {
      App.compileTemplate('search');
      App.search();
      App.subscribe();
    },

    search: function () {
      App.elements.body.on('submit', '#search', function (event) {
        event.preventDefault();

        var form = $(this);

        $.ajax({
          url: form.attr('action'),
          type: 'GET',
          data: form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          App.compileTemplate('search', data);
          App.compileTemplate('movie', data);
          App.compileTemplate('subscribe', data);
        });
      });
    },

    subscribe: function () {
      App.elements.body.on('submit', '#subscribe', function (event) {
        event.preventDefault();

        var form = $(this);

        $.ajax({
          url: form.attr('action'),
          type: 'GET',
          data: form.serialize()
        })
        .done(function (data) {
          App.compileTemplate('subscribe', data);

          // TODO: Show notification about successful subscription
        })
        .fail(function (xhr) {
          App.compileTemplate('subscribe', xhr.responseJSON);
        });
      });
    }

  };

  $(function () {
    App.init();
  });

})(jQuery);
