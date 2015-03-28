<?php

// Initialize PSR-4 autoload
require_once __DIR__.'/../vendor/autoload.php';

// Include used classes
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use BitolaCo\Silex\CapsuleServiceProvider;

// Init application
$app = new Application();
$app['debug'] = true;

// Register Twig provider
$app->register(new TwigServiceProvider(), [
  'twig.path' => __DIR__.'/../app/views',
  'twig.options' => [
    'strict_variables' => false,
  ],
]);

// Register Laravel Eloquent ORM provider
$app->register(new CapsuleServiceProvider(), [
 'capsule.connection' => [
    'driver' => 'sqlite',
    'database' => __DIR__.'/../'.getenv('DB_PATH'),
  ]
]);

// Declare common error handler for all exceptions
$app->error(function (\Exception $exception, $code) use ($app) {
  return $app->json([
    'params' => array_merge(
      $app['request']->query->all(),
      $app['request']->request->all()
    ),
    'error' => $exception->getMessage(),
  ], $exception->getCode());
});

// Define routes
$namespace = 'Premiefier\Controllers\\';
$app->get('/', $namespace.'Pages::subscribe');
$app->get('/unsubscribe', $namespace.'Pages::unsubscribe');
$app->get('/api/movies', $namespace.'Movies::index');
$app->get('/api/notifications', $namespace.'Notifications::index');
$app->post('/api/notifications', $namespace.'Notifications::create');
$app->delete('/api/notifications', $namespace.'Notifications::delete');
$app->match('/{slug}', $namespace.'Pages::error404');

return $app;
