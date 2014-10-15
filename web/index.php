<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

$app = new Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get('/', function (Application $app, Request $request) {

    $title = $request->query->get('title');
    $movie = false;

    if ($title) {
        $url = 'http://www.omdbapi.com/?' . http_build_query(array('t' => $title));
        $json = file_get_contents($url);
        $movie = json_decode($json);
    }

    return $app['twig']->render('actions/index.twig', array(
        'title' => $title,
        'movie' => $movie,
    ));
});

$app->run();
