<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\Movie;
use Premiefier\Models\Premiere;
use Premiefier\Models\User;
use Premiefier\Models\Notification;

class API {
  function search(Application $app) {
    $title = $app['request']->get('title');
    $movie = Movie::findOrFail($title);

    return $app->json([
      'title' => $title,
      'movie' => $movie,
    ]);
  }

  function subscribe(Application $app) {
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
  }

  function unsubscribe(Application $app) {
    // Remove email subscription from given movie
  }
}
