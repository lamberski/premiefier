<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\API;

class Search {
  function index(Application $app) {
    $title = $app['request']->get('title');
    $movies = $error = null;

    try {
      if (!$title) {
        throw new \Exception('Enter movie title.');
      }

      $movies = API::getMoviesByTitle($title);

    } catch (\Exception $e) {
      $error = $e->getMessage();
    }

    return $app->json([
      'params' => [
        'title' => $title,
      ],
      'movies' => $movies,
      'error' => $error,
    ], $error ? 400 : 200);
  }
}
