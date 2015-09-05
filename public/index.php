<?php

// Require bootstrap file
require_once __DIR__.'/../application/bootstrap.php';

// Declare common error handler for all exceptions
$application->error(function (\Exception $exception, $code) use ($application) {
  return $application->json([
    'error'  => $exception->getMessage(),
    'params' => array_merge(
      $application['request']->query->all(),
      $application['request']->request->all()
    ),
  ], $exception->getCode());
});

// Define public routes
$namespace = 'Premiefier\Controllers\\';
$application->get('/', $namespace.'Pages::subscribe');
$application->get('/unsubscribe', $namespace.'Pages::unsubscribe');
$application->get('/api/movies', $namespace.'Movies::index');
$application->get('/api/notifications', $namespace.'Notifications::index');
$application->post('/api/notifications', $namespace.'Notifications::create');
$application->delete('/api/notifications', $namespace.'Notifications::delete');
$application->match('/{slug}', $namespace.'Pages::error404');

// Start the engine
return $application->run();
