(function ($) {

  var App = {

    elements: {
      body: $('body')
    },

    templates: {
      search      : $('#search-template'),
      movies      : $('#movies-template'),
      subscribe   : $('#subscribe-template'),
      unsubscribe : $('#unsubscribe-template')
    },

    containers: {
      search      : $('#search-container'),
      movies      : $('#movies-container'),
      unsubscribe : $('#unsubscribe-container')
    },

    compileTemplate: function (name, data) {
      var template = Handlebars.compile(App.templates[name].html());
      return template(data);
    },

    disableSubmit: function (form) {
      var button = $('input[type="submit"]', form);
      button
        .val(button.data('submit'))
        .attr('disabled', 'disabled');
    },

    enableSubmit: function (form) {
      var button = $('input[type="submit"]', form);
      button
        .val(button.data('initial'))
        .removeAttr('disabled');
    },

    /**
     * Initialize features
     */
    init: function () {
      App.Unsubscribe.init();
      App.Search.init();
      App.Movie.init();
    },

    /**
     * Features for Search page
     */
    Search: {
      init: function () {
        if (App.containers.search.length === 0) return;

        App.containers.search.html(App.compileTemplate('search'));
        App.Search.bindSearchForm();
        App.Search.bindSubscribeForm();
      },

      bindSearchForm: function () {
        App.elements.body.on('submit', '#search-container form', function (event) {
          event.preventDefault();

          var form = $(this);
          App.disableSubmit(form);
          App.containers.movies.addClass('is-loading');

          $.ajax({
            url: form.attr('action'),
            type: 'GET',
            data: form.serialize()
          })
          .always(function (data) {
            data = data.responseJSON || data;
            App.containers.search.html(App.compileTemplate('search', data));
            App.containers.movies
              .html(App.compileTemplate('movies', data))
              .removeClass('is-loading');
          });
        });
      },

      bindSubscribeForm: function () {
        App.elements.body.on('submit', '.movie__form form', function (event) {
          event.preventDefault();

          var form = $(this);
          App.disableSubmit(form);

          $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize()
          })
          .done(function (data) {
          })
          .fail(function (xhr) {
          })
          .always(function (data) {
            data = data.responseJSON || data;
            App.enableSubmit(form);
          });
        });
      }
    },

    /**
     * Features for Unsubscribe page
     */
    Unsubscribe: {
      init: function () {
        if (App.containers.unsubscribe.length === 0) return;

        App.containers.unsubscribe.html(App.compileTemplate('unsubscribe'));
      }
    },

    /**
     * Features for Movies subscription
     */
    Movie: {
      init: function () {
        App.elements.body.on('click', '.movie', function () {
          var movie = $(this);
          var data = {params: {movie_id: movie.data('id')}};
          var container = movie.find('.movie__form');

          if (!movie.hasClass('is-open')) {
            movie.addClass('is-open');
            container.html(App.compileTemplate('subscribe', data));

            return false;
          }
        });

        App.elements.body.on('click', '[href="#show-details"]', function () {
          var movie = $(this).closest('.movie');
          movie.removeClass('is-open');

          return false;
        });
      }
    }

  };

  $(function () {
    App.init();
  });

})(jQuery);
