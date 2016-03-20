<?php

namespace Premiefier\Controllers;

use Silex\Application;

class Pages
{
    public function subscribe(Application $application)
    {
        return $application['twig']->render('pages/subscribe.twig');
    }

    public function unsubscribe(Application $application)
    {
        return $application['twig']->render('pages/unsubscribe.twig');
    }

    public function error404(Application $application)
    {
        return $application['twig']->render('pages/error404.twig');
    }
}
