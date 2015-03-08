<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\API;
use Premiefier\Models\Premiere;
use Premiefier\Models\User;
use Premiefier\Models\Notification;

class Notifications {
  function create(Application $app) {
    $id = $app['request']->get('id');
    $email = $app['request']->get('email');

    // TODO: Throw an exception if $email or $title is empty

    // 1. Get movie info from OMDb
    $movie = API::getMovieByID($id);

    // 2. Fetch or create premiere
    $premiere = Premiere::firstOrCreate([
      'title' => $movie->title,
      'released_at' => $movie->release_dates->theater,
    ]);

    // 3. Fetch or create user
    $user = User::firstOrCreate(['email' => $email]);

    // 4. Create notification (or throw error about already being subscribed)
    $notification = Notification::firstOrNew([
      'premiere_id' => $premiere->id,
      'user_id' => $user->id,
    ]);

    if ($notification->id) {
      throw new \Exception(sprintf('You are already subscribed to %s.', $movie->title));
    } else {
      $notification->save();
    }

    return $app->json([
      'user' => $user,
      'movie' => $movie,
    ]);
  }

  function delete(Application $app) {
    // Remove email subscription from given movie
  }
}
