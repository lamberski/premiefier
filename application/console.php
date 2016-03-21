<?php

use Premiefier\Commands\Notify;

$application = require_once __DIR__ . '/bootstrap.php';

$application['console']->add(new Notify());
$application['console']->run();
