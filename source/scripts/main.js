(function ($) {

  var Elements = {

    body: $('body'),

    templates: {
      search        : $('#search-template'),
      movies        : $('#movies-template'),
      notifications : $('#notifications-template'),
      subscribe     : $('#subscribe-template'),
      unsubscribe   : $('#unsubscribe-template')
    },

    containers: {
      search        : $('#search-container'),
      movies        : $('#movies-container'),
      notifications : $('#notifications-container'),
      unsubscribe   : $('#unsubscribe-container')
    }

  };

  var Helpers = {

    compileTemplate: function (name, data) {
      var template = Handlebars.compile(Elements.templates[name].html());
      return template(data);
    }
  };

  var Subscribe = {

    init: function () {
      if (Elements.containers.search.length === 0) {
        return;
      }

      Elements.containers.search.html(Helpers.compileTemplate('search'));
      Subscribe.bindSearchForm();
      Subscribe.bindSubscribeForm();
      Subscribe.bindTogglingMovieDetails();
    },

    bindSearchForm: function () {
      Elements.body.on('submit', '#search-container form', function (event) {
        var form = $(this).addClass('form--loading');

        Elements.containers.movies.addClass('loadable--loading');

        $.ajax({
          url  : form.attr('action'),
          type : form.attr('method'),
          data : form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          form.removeClass('form--loading');
          Elements.containers.search
            .html(Helpers.compileTemplate('search', data));
          Elements.containers.movies
            .html(Helpers.compileTemplate('movies', data))
            .removeClass('loadable--loading');
        });

        event.preventDefault();
      });
    },

    bindSubscribeForm: function () {
      Elements.body.on('submit', '.movie__form form', function (event) {
        var form      = $(this);
        var movie     = form.closest('.movie');
        var container = form.closest('.movie__form');

        $.ajax({
          url  : form.attr('action'),
          type : form.attr('method'),
          data : form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          container.html(Helpers.compileTemplate('subscribe', data));
        });

        event.preventDefault();
      });
    },

    bindTogglingMovieDetails: function () {
      Elements.body.on('click', '.movie--available', function (event) {
        var movie     = $(this);
        var data      = { params: { movie_id: movie.data('id') } };
        var container = movie.find('.movie__form');

        if (!movie.hasClass('is-open') && !$(event.target).is('a')) {
          movie.addClass('is-open');
          container.html(Helpers.compileTemplate('subscribe', data));
          container.find('input[name="email"]').attr('autofocus', true);

          return false;
        }
      });

      Elements.body.on('click', '[href="#show-details"]', function (event) {
        var movie = $(this).closest('.movie');
        movie.removeClass('is-open');

        event.preventDefault();
      });
    }

  };

  var Unsubscribe = {

    init: function () {
      if (Elements.containers.unsubscribe.length === 0) {
        return;
      }

      Elements.containers.unsubscribe.html(Helpers.compileTemplate('unsubscribe'));
      Unsubscribe.bindSearchForm();
      Unsubscribe.bindUnbscribeForm();
    },

    bindSearchForm: function () {
      Elements.body.on('submit', '#unsubscribe-container form', function (event) {
        var form = $(this).addClass('form--loading');

        Elements.containers.movies.addClass('loadable--loading');

        $.ajax({
          url  : form.attr('action'),
          type : form.attr('method'),
          data : form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          form.removeClass('form--loading');
          Elements.containers.unsubscribe
            .html(Helpers.compileTemplate('unsubscribe', data));
          Elements.containers.notifications
            .html(Helpers.compileTemplate('notifications', data))
            .removeClass('loadable--loading');
        });

        event.preventDefault();
      });
    },

    bindUnbscribeForm: function () {
      Elements.body.on('submit', '.notification form', function (event) {
        var form         = $(this);
        var notification = form.closest('.notification');

        $.ajax({
          url  : form.attr('action'),
          type : form.attr('method'),
          data : form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          notification.fadeOut(300, function() { $(this).remove(); });
        });

        event.preventDefault();
      });
    }

  };

  $(function () {
    Subscribe.init();
    Unsubscribe.init();
  });

})(jQuery);
