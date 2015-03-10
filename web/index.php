<?php

//------------------------------------------------------------------------------
// Initialization of the application
//------------------------------------------------------------------------------

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use BitolaCo\Silex\CapsuleServiceProvider;

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

$app->register(new CapsuleServiceProvider(), [
 'capsule.connection' => [
    'driver'   => 'sqlite',
    'database' => __DIR__.'/../db.sqlite',
  ]
]);

//------------------------------------------------------------------------------
// Common actions
//------------------------------------------------------------------------------

$app->before(function () {
  // TODO: Checking if request is from the same domain
});

$app->error(function (\Exception $exception, $code) use ($app) {
  return $app->json([
    'error' => $exception->getMessage(),
    'title' => $app['request']->get('title'),
    'email' => $app['request']->get('email'),
  ], 404);
});

//------------------------------------------------------------------------------
// Routes
//------------------------------------------------------------------------------

$app->get('/', 'Premiefier\Controllers\Pages::subscribe');
$app->get('/unsubscribe', 'Premiefier\Controllers\Pages::unsubscribe');
$app->get('/api/search', 'Premiefier\Controllers\Search::index');
$app->get('/api/notifications', 'Premiefier\Controllers\Notifications::index');
$app->post('/api/notifications', 'Premiefier\Controllers\Notifications::create');
$app->delete('/api/notifications', 'Premiefier\Controllers\Notifications::delete');
$app->match('/{slug}', 'Premiefier\Controllers\Pages::error404');

//------------------------------------------------------------------------------
// Starting the application
//------------------------------------------------------------------------------

$app->run();
