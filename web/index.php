<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\TwigServiceProvider;

$app = new Application;

$app->register(new TwigServiceProvider, array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/', function (Application $app, Request $request) {

    $title = $request->query->get('title');
    $movie = false;

    if ($title) {
        $url = 'http://www.omdbapi.com/?' . http_build_query(array('t' => $title));
        $json = file_get_contents($url);
        $movie = json_decode($json);
        $already_released = strtotime($movie->Released) < time();
    }

    return $app['twig']->render('actions/index.twig', array(
        'title' => $title,
        'movie' => $movie,
        'already_released' => $already_released,
    ));
});

$app->run();
