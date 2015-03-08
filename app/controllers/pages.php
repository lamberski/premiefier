<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\User;

class Pages {
  function subscribe(Application $app) {
    return $app['twig']->render('actions/subscribe.twig');
  }

  function unsubscribe(Application $app) {
    // $email = $app['request']->get('email');
    // $user = User::where('email', $email)->firstOrFail();
    // $notifications = $user->notifications();

    return $app['twig']->render('actions/unsubscribe.twig');
  }

  function error404(Application $app) {
    return $app['twig']->render('actions/subscribe.twig', [
      'error404' => true
    ]);
  }
}
