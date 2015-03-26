<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\API;
use Premiefier\Models\Premiere;
use Premiefier\Models\User;
use Premiefier\Models\Notification;

class Notifications {

  function index(Application $app)
  {
    $email = $app['request']->get('email');

    if (!$email)
    {
      throw new \Exception('Enter your email address first.', 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      throw new \Exception('Please enter valid email address.', 400);
    }

    $user = User::whereEmail($email)->first();

    $notifications = $user
      ? $user->notifications()->with('premiere')->get()
      : null;

    return $app->json([
      'params' => $app['request']->query->all(),
      'user' => $user,
      'notifications' => $notifications,
    ]);
  }

  function create(Application $app)
  {
    $movieID = $app['request']->get('movie_id');
    $email = $app['request']->get('email');

    if (!$movieID)
    {
      throw new \Exception('Provide movie ID from Rotten Tomatoes API.', 400);
    }

    if (!$email)
    {
      throw new \Exception('Enter your email address first.', 400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
      throw new \Exception('Please enter valid email address.', 400);
    }

    $movie = API::getMovieByID($movieID);

    $premiere = Premiere::firstOrCreate([
      'id' => $movieID,
      'released_at' => $movie['release_dates']['theater'],
      'title' => $movie['title'],
      'poster_url' => $movie['posters']['thumbnail'],
      'details_url' => $movie['links']['alternate'],
    ]);

    $user = User::firstOrCreate(['email' => $email]);

    $notification = Notification::firstOrNew([
      'user_id' => $user->id,
      'premiere_id' => $movieID,
    ]);

    if ($notification->id)
    {
      throw new \Exception(sprintf('You are already subscribed to %s!', $movie['title']), 404);
    }
    else
    {
      $notification->save();
    }

    return $app->json([
      'params' => $app['request']->request->all(),
      'user' => $user,
      'movie' => $movie,
    ]);
  }

  function delete(Application $app)
  {
    $notificationID = $app['request']->get('notification_id');

    if (!$notificationID)
    {
      throw new \Exception('Provide notification ID.', 400);
    }

    Notification::destroy($notificationID);

    return $app->json([
      'params' => $app['request']->request->all(),
    ]);
  }

}
