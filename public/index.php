<?php

// Require bootstrap file
require_once __DIR__.'/../app/bootstrap.php';

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

// Define public routes
$namespace = 'Premiefier\Controllers\\';
$app->get('/', $namespace.'Pages::subscribe');
$app->get('/unsubscribe', $namespace.'Pages::unsubscribe');
$app->get('/api/movies', $namespace.'Movies::index');
$app->get('/api/notifications', $namespace.'Notifications::index');
$app->post('/api/notifications', $namespace.'Notifications::create');
$app->delete('/api/notifications', $namespace.'Notifications::delete');
$app->match('/{slug}', $namespace.'Pages::error404');

// Start the engine
return $app->run();
