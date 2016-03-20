<?php

// Include used classes
use Premiefier\Commands\Notify;

// Require bootstrap file
$application = require_once __DIR__ . '/bootstrap.php';

// Add available console commands
$application['console']->add(new Notify());

// Start the engine
$application['console']->run();
