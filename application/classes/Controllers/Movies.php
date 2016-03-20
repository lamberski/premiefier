<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\API;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Movies
{
    public function index(Application $application)
    {
        $title = $application['request']->get('title');

        if (!trim($title)) {
            throw new HttpException(400, 'Enter movie title.');
        }

        return $application->json([
            'params' => $application['request']->query->all(),
            'movies' => API::getMoviesByTitle($application, $title),
        ]);
    }
}
