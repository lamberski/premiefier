<?php

//------------------------------------------------------------------------------
// Initialization of the application
//------------------------------------------------------------------------------

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/models/movie.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
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

$app->register(new DoctrineServiceProvider(), [
  'db.options' => [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/../db.sqlite',
  ],
]);

//------------------------------------------------------------------------------
// Common actions
//------------------------------------------------------------------------------

$app->before(function ($request) {
  // TODO: Checking if request is from the same domain
});

$app->error(function (\Exception $exception, $code) use ($app) {
  $request = $app['request'];

  return $app->json([
    'error' => $exception->getMessage(),
    'title' => $request->get('title'),
    'email' => $request->get('email'),
  ], 404);
});

//------------------------------------------------------------------------------
// Routes: Pages
//------------------------------------------------------------------------------

$app->get('/', function (Application $app, Request $request) {
  return $app['twig']->render('actions/subscribe.twig');
});

$app->get('/unsubscribe', function (Application $app, Request $request) {
  return $app['twig']->render('actions/unsubscribe.twig');
});

//------------------------------------------------------------------------------
// Routes: API
//------------------------------------------------------------------------------

$app->get('/api/search', function (Application $app, Request $request) {
  $title = $request->get('title');
  $movie = new Movie($title);

  return $app->json([
    'title' => $title,
    'movie' => $movie,
  ]);
});

$app->get('/api/subscribe', function (Application $app, Request $request) {
  $db    = $app['db'];
  $title = $request->get('title');
  $email = $request->get('email');

  // TODO: Throw an exception if $email is empty

  // 1. Get movie info from IMDB
  $movie = new Movie($title);

  // 2. Fetch movie (if exists)
  $movieInDb = $db->createQueryBuilder()
    ->select('id', 'title', 'released_at')
    ->from('movies')
    ->where('title = ?')
    ->setParameter(0, $title)
    ->execute()
    ->fetch();

  if ($movieInDb) {
    $movieID = $movieInDb['id'];

  // 3. If not, create one
  } else {
    $db->createQueryBuilder()
      ->insert('movies')
      ->setValue('title', '?')
      ->setValue('released_at', '?')
      ->setParameter(0, $movie->title)
      ->setParameter(1, $movie->releasedAt)
      ->execute();

    $movieID = $db->lastInsertID();
  }

  // 4. Fetch user (if exists)
  $user = $db->createQueryBuilder()
    ->select('id', 'email')
    ->from('users')
    ->where('email = ?')
    ->setParameter(0, $email)
    ->execute()
    ->fetch();

  if ($user) {
    $userID = $user['id'];

  // 5. If not, create one
  } else {
    $db->createQueryBuilder()
      ->insert('users')
      ->setValue('email', '?')
      ->setParameter(0, $email)
      ->execute();

    $userID = $db->lastInsertID();
  }

  // 5. Check if user already subscribed to the movie
  $notification = $db->createQueryBuilder()
    ->select('id')
    ->from('notifications')
    ->where('user_id = ?')
    ->andWhere('movie_id = ?')
    ->setParameter(0, $userID)
    ->setParameter(1, $movieID)
    ->execute()
    ->fetch();

  if ($notification) {
    throw new \Exception(sprintf('You are already subscribed to %s.', $movie->title));

  // 6. Create link between user and movie
  } else {
    $db->createQueryBuilder()
      ->insert('notifications')
      ->setValue('user_id', '?')
      ->setValue('movie_id', '?')
      ->setParameter(0, $userID)
      ->setParameter(1, $movieID)
      ->execute();
  }

  return $app->json([
    'user'  => $user,
    'movie' => $movie,
    'title' => $request->get('title'),
    'email' => $request->get('email'),
  ]);
});

$app->delete('/api/unsubscribe', function (Application $app, Request $request) {
  // Remove email subscription from given movie
});

//------------------------------------------------------------------------------
// Starting the application
//------------------------------------------------------------------------------

$app->run();
