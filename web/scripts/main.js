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
    },

    changeButtonState: function (form, label, disabled) {
      var button = $('input[type="submit"]', form);
      button.val(button.data(label));

      if (disabled) {
        button.attr('disabled', true);
      } else {
        button.removeAttr('disabled');
      }
    }

  };

  var Subscribe = {

    init: function () {
      if (Elements.containers.search.length === 0) return;

      Elements.containers.search.html(Helpers.compileTemplate('search'));
      Subscribe.bindSearchForm();
      Subscribe.bindSubscribeForm();
      Subscribe.bindTogglingMovieDetails();
    },

    bindSearchForm: function () {
      Elements.body.on('submit', '#search-container form', function (event) {
        event.preventDefault();

        var form = $(this);

        Helpers.changeButtonState(form, 'processing', true);
        Elements.containers.movies.addClass('is-loading');

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          Elements.containers.search
            .html(Helpers.compileTemplate('search', data));
          Elements.containers.movies
            .html(Helpers.compileTemplate('movies', data))
            .removeClass('is-loading');
        });
      });
    },

    bindSubscribeForm: function () {
      Elements.body.on('submit', '.movie__form form', function (event) {
        event.preventDefault();

        var form = $(this);

        var movie = form.closest('.movie');
        var container = form.closest('.movie__form');

        Helpers.changeButtonState(form, 'processing', true);

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          container.html(Helpers.compileTemplate('subscribe', data));
        });
      });
    },

    bindTogglingMovieDetails: function () {
      Elements.body.on('click', '.movie', function (event) {
        var movie = $(this);
        var data = {params: {movie_id: movie.data('id')}};
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

        return false;
      });
    }

  };

  var Unsubscribe = {

    init: function () {
      if (Elements.containers.unsubscribe.length === 0) return;

      Elements.containers.unsubscribe.html(Helpers.compileTemplate('unsubscribe'));
      Unsubscribe.bindSearchForm();
      Unsubscribe.bindUnbscribeForm();
    },

    bindSearchForm: function () {
      Elements.body.on('submit', '#unsubscribe-container form', function (event) {
        event.preventDefault();

        var form = $(this);

        Helpers.changeButtonState(form, 'processing', true);
        Elements.containers.movies.addClass('is-loading');

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          Elements.containers.unsubscribe
            .html(Helpers.compileTemplate('unsubscribe', data));
          Elements.containers.notifications
            .html(Helpers.compileTemplate('notifications', data))
            .removeClass('is-loading');
        });
      });
    },

    bindUnbscribeForm: function () {
      Elements.body.on('submit', '.notification form', function (event) {
        event.preventDefault();

        var form = $(this);
        var notification = form.closest('.notification');

        Helpers.changeButtonState(form, 'processing', true);

        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          data: form.serialize()
        })
        .always(function (data) {
          data = data.responseJSON || data;
          Helpers.changeButtonState(form, 'primary', false);
          notification.fadeOut(300, function() { $(this).remove(); });
        });
      });
    }

  };

  var Application = {

    init: function () {
      Subscribe.init();
      Unsubscribe.init();
    }

  };

  $(function () {
    Application.init();
  });

})(jQuery);
