<?php

//------------------------------------------------------------------------------
// Initialization of the application
//------------------------------------------------------------------------------

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/models/movie.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Premiefier\Movie;

$app = new Application();
$app['debug'] = true;

//------------------------------------------------------------------------------
// Registration of services
//------------------------------------------------------------------------------

$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__.'/../app/views',
    'twig.options' => [
        'strict_variables' => false,
    ],
]);

//------------------------------------------------------------------------------
// Common actions
//------------------------------------------------------------------------------

$app->before(function ($request) {
});

//------------------------------------------------------------------------------
// Routes: Pages
//------------------------------------------------------------------------------

$app->get('/', function (Application $app, Request $request) {
    return $app['twig']->render('actions/subscribe.twig');
});

$app->get('/unsubscribe', function (Application $app, Request $request) {
    return $app['twig']->render('actions/unsubscribe.twig');
});

//------------------------------------------------------------------------------
// Routes: API
//------------------------------------------------------------------------------

$app->post('/api/search', function (Application $app, Request $request) {
    $title = $request->get('title');
    $movie = $error = null;

    if ($title) {
      $movie = new Movie($title);

      if (!$movie->wasFound) {
        $error = 'Movie was not found.';
      }

      if (!$movie->releasedAt) {
        $error = 'Release date of this movie is not available.';
      }

      if ($movie->wasAlreadyReleased) {
        $error = 'This movie was already released.';
      }
    } else {
      $error = 'Enter movie title.';
    }

    return $app->json([
      'title' => $title,
      'movie' => $movie,
      'error' => $error
    ]);
});

$app->post('/api/subscribe', function (Application $app, Request $request) {
    // Add email subscription for given movie
});

$app->delete('/api/unsubscribe', function (Application $app, Request $request) {
    // Remove email subscription from given movie
});

//------------------------------------------------------------------------------
// Starting the application
//------------------------------------------------------------------------------

$app->run();
