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
// Routes: Search
//------------------------------------------------------------------------------

$app->get('/', function (Application $app, Request $request) {
    return $app['twig']->render('actions/index.twig');
});

$app->post('/', function (Application $app, Request $request) {
    $title = $request->get('title');
    $movie = nil;

    if ($title) {
        $movie = new Movie($title);
    }

    return $app['twig']->render('actions/index.twig', [
        'title' => $title,
        'movie' => $movie,
    ]);
});

//------------------------------------------------------------------------------
// Routes: Subscription
//------------------------------------------------------------------------------

$app->post('/subscribe', function (Application $app, Request $request) {
    $title = $request->get('title');
    $movie = nil;

    if ($title) {
        $movie = new Movie($title);
    }

    return $app['twig']->render('actions/index.twig', [
        'title' => $title,
        'movie' => $movie,
        'email' => $email,
    ]);
});

//------------------------------------------------------------------------------
// Starting the application
//------------------------------------------------------------------------------

$app->run();
