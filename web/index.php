<?php

//------------------------------------------------------------------------------
// Initialization of the application
//------------------------------------------------------------------------------

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use BitolaCo\Silex\CapsuleServiceProvider;
use Premiefier\Models\Movie;
use Premiefier\Models\Premiere;
use Premiefier\Models\User;
use Premiefier\Models\Notification;

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
// Routes: Pages
//------------------------------------------------------------------------------

$app->get('/', function (Application $app) {
  return $app['twig']->render('actions/subscribe.twig');
});

$app->get('/unsubscribe', function (Application $app) {
  return $app['twig']->render('actions/unsubscribe.twig');
});

//------------------------------------------------------------------------------
// Routes: API
//------------------------------------------------------------------------------

$app->get('/api/search', function (Application $app) {
  $title = $app['request']->get('title');
  $movie = Movie::findOrFail($title);

  return $app->json([
    'title' => $title,
    'movie' => $movie,
  ]);
});

$app->get('/api/subscribe', function (Application $app) {
  $db    = $app['db'];
  $title = $app['request']->get('title');
  $email = $app['request']->get('email');

  // TODO: Throw an exception if $email is empty

  // 1. Get movie info from OMDb
  $movie = Movie::findOrFail($title);

  // 2. Fetch or create premiere
  $premiere = Premiere::firstOrCreate([
    'title'       => $movie->title,
    'released_at' => $movie->releasedAt,
  ]);

  // 3. Fetch or create user
  $user = User::firstOrCreate(['email' => $email]);

  // 4. Create notification (or throw error about already being subscribed)
  $notification = Notification::firstOrNew([
    'premiere_id' => $premiere->id,
    'user_id'     => $user->id,
  ]);

  if ($notification->count() == 0) {
    $notification->save();
  } else {
    throw new \Exception(sprintf('You are already subscribed to %s.', $movie->title));
  }

  return $app->json([
    'user'  => $user,
    'movie' => $movie,
    'title' => $app['request']->get('title'),
    'email' => $app['request']->get('email'),
  ]);
});

$app->delete('/api/unsubscribe', function (Application $app) {
  // Remove email subscription from given movie
});

//------------------------------------------------------------------------------
// Starting the application
//------------------------------------------------------------------------------

$app->run();
