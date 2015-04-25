<?php

use Premiefier\Commands\Notify;

// Require bootstrap file
$app = require_once __DIR__.'/bootstrap.php';

$application = $app['console'];
$application->add(new Notify());
$application->run();
