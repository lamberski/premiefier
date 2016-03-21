//= include ../../bower_components/jquery/dist/jquery.js
//= include ../../bower_components/handlebars/handlebars.js

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
    compileTemplate: function ($container, name, data) {
      var template = Handlebars.compile(Elements.templates[name].html());
      var $element = $container instanceof jQuery ? $container : Elements.containers[$container];

      $element.html(template(data)).removeClass('loadable--loading');

      var $autofocus = $element.find('input[autofocus]');
      if ($autofocus) {
        $autofocus.trigger('focus').val($autofocus.val());
      }
    },

    submitForm: function ($form, callback) {
      return $.ajax({
        url  : $form.attr('action'),
        type : $form.attr('method'),
        data : $form.serialize()
      })
      .always(function (data) {
        data = data.responseJSON || data;
        callback(data);
      });
    }
  };

  var Subscribe = {
    init: function () {
      if (Elements.containers.search.length === 0) {
        return;
      }

      Helpers.compileTemplate('search', 'search');
      Subscribe.bindSearchForm();
      Subscribe.bindSubscribeForm();
      Subscribe.bindTogglingMovieDetails();
    },

    bindSearchForm: function () {
      Elements.body.on('submit', '#search-container form', function (event) {
        var $form = $(this).addClass('form--loading');

        Elements.containers.movies.addClass('loadable--loading');

        Helpers.submitForm($form, function (data) {
          Helpers.compileTemplate('search', 'search', data);
          Helpers.compileTemplate('movies', 'movies', data);
        });

        event.preventDefault();
      });
    },

    bindSubscribeForm: function () {
      Elements.body.on('submit', '.movie__form form', function (event) {
        var $form      = $(this).addClass('form--loading');
        var $movie     = $form.closest('.movie');
        var $container = $form.closest('.movie__form');

        Helpers.submitForm($form, function (data) {
          Helpers.compileTemplate($container, 'subscribe', data);
        });

        event.preventDefault();
      });
    },

    bindTogglingMovieDetails: function () {
      Elements.body.on('click', '.movie--available', function (event) {
        var $movie     = $(this);
        var $container = $movie.find('.movie__form');
        var data      = { params: { movie_id: $movie.data('id') } };

        if (!$movie.hasClass('movie--open') && !$(event.target).is('a')) {
          $movie.addClass('movie--open');
          Helpers.compileTemplate($container, 'subscribe', data);

          return false;
        }
      });

      Elements.body.on('click', '[href="#show-details"]', function (event) {
        var $movie = $(this).closest('.movie');
        $movie.removeClass('movie--open');

        event.preventDefault();
      });
    }
  };

  var Unsubscribe = {
    init: function () {
      if (Elements.containers.unsubscribe.length === 0) {
        return;
      }

      Helpers.compileTemplate('unsubscribe', 'unsubscribe');
      Unsubscribe.bindSearchForm();
      Unsubscribe.bindUnbscribeForm();
    },

    bindSearchForm: function () {
      Elements.body.on('submit', '#unsubscribe-container form', function (event) {
        var $form = $(this).addClass('form--loading');

        Elements.containers.movies.addClass('loadable--loading');

        Helpers.submitForm($form, function (data) {
          Helpers.compileTemplate('unsubscribe', 'unsubscribe', data);
          Helpers.compileTemplate('notifications', 'notifications', data);
        });

        event.preventDefault();
      });
    },

    bindUnbscribeForm: function () {
      Elements.body.on('submit', '.notification form', function (event) {
        var $form         = $(this);
        var $notification = $form.closest('.notification');

        Helpers.submitForm($form, function (data) {
          $notification.addClass('movie--fading');
          setTimeout(function () { $notification.remove(); }, 300);
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
