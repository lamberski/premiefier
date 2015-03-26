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
    $user = $notifications = $error = null;

    try
    {
      if (!$email)
      {
        throw new \Exception('Enter your email address first.');
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      {
        throw new \Exception('Please enter valid email address.');
      }

      $user = User::whereEmail($email)->first();

      if (!$user)
      {
        throw new \Exception('You are not subscribed to any movie premiere yet.');
      }

      $notifications = $user->notifications()->with('premiere')->get();

    }
    catch (\Exception $e)
    {
      $error = $e->getMessage();
    }

    return $app->json([
      'params' => [
        'email' => $email,
      ],
      'user' => $user,
      'notifications' => $notifications,
      'error' => $error,
    ], $error ? 400 : 200);
  }

  function create(Application $app)
  {
    $movieID = $app['request']->get('movie_id');
    $email = $app['request']->get('email');
    $user = $movie = $error = null;

    try
    {
      if (!$movieID)
      {
        throw new \Exception('Provide movie ID from Rotten Tomatoes API.');
      }

      if (!$email)
      {
        throw new \Exception('Enter your email address first.');
      }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      {
        throw new \Exception('Please enter valid email address.');
      }

      // 1. Get movie info from OMDb
      $movie = API::getMovieByID($movieID);

      // 2. Fetch or create premiere
      $premiere = Premiere::firstOrCreate([
        'id' => $movieID,
        'released_at' => $movie['release_dates']['theater'],
        'title' => $movie['title'],
        'poster_url' => $movie['posters']['thumbnail'],
        'details_url' => $movie['links']['alternate'],
      ]);

      // 3. Fetch or create user
      $user = User::firstOrCreate(['email' => $email]);

      // 4. Create notification (or throw error about already being subscribed)
      $notification = Notification::firstOrNew([
        'user_id' => $user->id,
        'premiere_id' => $movieID,
      ]);

      if ($notification->id)
      {
        throw new \Exception(sprintf('You are already subscribed to %s!', $movie['title']));
      }
      else
      {
        $notification->save();
      }
    }
    catch (\Exception $e)
    {
      $error = $e->getMessage();
    }

    return $app->json([
      'params' => [
        'movie_id' => $movieID,
        'email' => $email,
      ],
      'user' => $user,
      'movie' => $movie,
      'error' => $error,
    ], $error ? 400 : 200);
  }

  function delete(Application $app)
  {
    $notificationID = $app['request']->get('notification_id');
    $error = null;

    try
    {
      if (!$notificationID)
      {
        throw new \Exception('Provide notification ID.');
      }

      $notification = Notification::destroy($notificationID);
    }
    catch (\Exception $e)
    {
      $error = $e->getMessage();
    }

    return $app->json([
      'params' => [
        'notification_id' => $notificationID,
      ],
      'error' => $error,
    ], $error ? 400 : 200);
  }

}
