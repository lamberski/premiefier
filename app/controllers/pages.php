<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\User;

class Pages {

  function subscribe(Application $app)
  {
    return $app['twig']->render('pages/subscribe.twig');
  }

  function unsubscribe(Application $app)
  {
    return $app['twig']->render('pages/unsubscribe.twig');
  }

  function error404(Application $app)
  {
    return $app['twig']->render('pages/error404.twig');
  }

}
