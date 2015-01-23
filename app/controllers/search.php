<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\Movie;

class Search {
  function index(Application $app) {
    $title = $app['request']->get('title');

    if (!$title) {
      throw new \Exception('Enter movie title.');
    }

    $movie = Movie::findOrFail($title);

    return $app->json([
      'title' => $title,
      'movie' => $movie,
    ]);
  }
}
