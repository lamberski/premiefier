<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\API;

class Movies {

  function index(Application $app)
  {
    $title = $app['request']->get('title');

    if (!$title)
    {
      throw new \Exception('Enter movie title.', 400);
    }

    return $app->json([
      'params' => $app['request']->query->all(),
      'movies' => API::getMoviesByTitle($title),
    ]);
  }

}
