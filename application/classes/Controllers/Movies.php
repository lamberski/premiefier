<?php

namespace Premiefier\Controllers;

use Premiefier\Models\API;
use Silex\Application;

class Movies
{
    public function index(Application $application)
    {
        $title = $application['request']->get('title');

        if (!trim($title)) {
            throw new \Exception('Enter movie title.', 400);
        }

        return $application->json([
            'params' => $application['request']->query->all(),
            'movies' => API::getMoviesByTitle($title),
        ]);
    }
}