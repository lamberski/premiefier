(function ($) {

  var App = {

    elements: {
      body: $('body')
    },

    templates: {
      search      : $('#search-template'),
      movie       : $('#movie-template'),
      subscribe   : $('#subscribe-template'),
      unsubscribe : $('#unsubscribe-template'),
      movies      : $('#movies-template')
    },

    containers: {
      search      : $('#search-container'),
      movie       : $('#movie-container'),
      subscribe   : $('#subscribe-container'),
      unsubscribe : $('#unsubscribe-container'),
      movies      : $('#movies-container')
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
      // Initialize modules related to current page
      if (App.containers.subscribe.length > 0) {
        App.Subscribe.init();
      }
      if (App.containers.unsubscribe.length > 0) {
        App.Unsubscribe.init();
      }
    },

    /**
     * Features for Subscribe page
     */
    Subscribe: {
      init: function () {
        App.compileTemplate('search');
        App.Subscribe.bindSearchForm();
        App.Subscribe.bindSubscribeForm();
      },

      bindSearchForm: function () {
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

      bindSubscribeForm: function () {
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
    },

    /**
     * Features for Unsubscribe page
     */
    Unsubscribe: {
      init: function () {
      }
    }

  };

  $(function () {
    App.init();
  });

})(jQuery);
