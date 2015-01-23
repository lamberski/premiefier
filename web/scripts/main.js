(function ($) {

  var App = {

    elements: {
      body: $('body')
    },

    templates: {
      search    : $('#search-template'),
      movie     : $('#movie-template'),
      subscribe : $('#subscribe-template'),
      email     : $('#email-template'),
      movies    : $('#movies-template')
    },

    containers: {
      search    : $('#search-container'),
      movie     : $('#movie-container'),
      subscribe : $('#subscribe-container'),
      email     : $('#email-container'),
      movies    : $('#movies-container')
    },

    compileTemplate: function (name, data) {
      var template = Handlebars.compile(App.templates[name].html());
      App.containers[name].html(template(data));
    },

    disableSubmit: function (form) {
      var button = $('input[type="submit"]', form);
      button
        .val(button.data('processing'))
        .attr('disabled', 'disabled');
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
        App.disableSubmit(form);
        App.compileTemplate('movie');
        App.compileTemplate('subscribe');

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
        App.disableSubmit(form);

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
