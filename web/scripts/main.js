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
      App.containers[name].empty().append(template(data));
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
          type: 'POST',
          data: form.serialize()
        })
        .done(function (data) {
          App.compileTemplate('search', data);
          App.compileTemplate('movie', data);
          App.compileTemplate('subscribe', data);
        })
        .fail(function (xhr) {
          App.compileTemplate('search', xhr.responseJSON);
        });
      });
    },

    subscribe: function () {
      App.elements.body.on('submit', '#subscribe', function (event) {
        event.preventDefault();

        var form = $(this);

        $.ajax({
          url: form.attr('action'),
          type: 'POST',
          data: form.serialize()
        })
        .done(function (data) {

        })
        .fail(function (xhr) {
          var data = xhr.responseJSON;
        });
      });
    }

  };

  $(function () {
    App.init();
  });

})(jQuery);
