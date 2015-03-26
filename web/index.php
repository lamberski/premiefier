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
    'params' => array_merge(
      $app['request']->query->all(),
      $app['request']->request->all()
    ),
    'error' => $exception->getMessage(),
  ], $exception->getCode());
});

//------------------------------------------------------------------------------
// Routes
//------------------------------------------------------------------------------

$namespace = 'Premiefier\Controllers\\';

$app->get('/', $namespace.'Pages::subscribe');
$app->get('/unsubscribe', $namespace.'Pages::unsubscribe');
$app->get('/api/search', $namespace.'Search::index');
$app->get('/api/notifications', $namespace.'Notifications::index');
$app->post('/api/notifications', $namespace.'Notifications::create');
$app->delete('/api/notifications', $namespace.'Notifications::delete');
$app->match('/{slug}', $namespace.'Pages::error404');

//------------------------------------------------------------------------------
// Starting the application
//------------------------------------------------------------------------------

$app->run();
