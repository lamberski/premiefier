<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\User;

class Pages {

  function subscribe(Application $app)
  {
    return $app['twig']->render('actions/subscribe.twig');
  }

  function unsubscribe(Application $app)
  {
    return $app['twig']->render('actions/unsubscribe.twig');
  }

  function error404(Application $app)
  {
    return $app['twig']->render('actions/error404.twig');
  }

}
