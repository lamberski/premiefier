<?php

namespace Premiefier\Controllers;

use Silex\Application;

class Pages {
  function subscribe(Application $app) {
    return $app['twig']->render('actions/subscribe.twig');
  }

  function unsubscribe(Application $app) {
    return $app['twig']->render('actions/unsubscribe.twig');
  }
}
