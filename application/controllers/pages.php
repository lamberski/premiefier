<?php

namespace Premiefier\Controllers;

use Silex\Application;
use Premiefier\Models\User;

class Pages {

  function subscribe(Application $application)
  {
    return $application['twig']->render('pages/subscribe.twig');
  }

  function unsubscribe(Application $application)
  {
    return $application['twig']->render('pages/unsubscribe.twig');
  }

  function error404(Application $application)
  {
    return $application['twig']->render('pages/error404.twig');
  }

}
