<?php

// Initialize PSR-4 autoload
require_once __DIR__.'/../vendor/autoload.php';

// Include used classes
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use BitolaCo\Silex\CapsuleServiceProvider;
use Knp\Provider\ConsoleServiceProvider;

// Init application
$application = new Application();
$application['debug'] = getenv('ENVIRONMENT') == 'development';

// Register Twig provider
$application->register(new TwigServiceProvider(), [
  'twig.path' => __DIR__.'/../app/views',
  'twig.options' => [
    'strict_variables' => false,
  ],
]);

// Register Laravel Eloquent ORM provider
$application->register(new CapsuleServiceProvider(), [
 'capsule.connection' => [
    'driver' => 'sqlite',
    'database' => __DIR__.'/../'.getenv('DB_PATH'),
  ]
]);

// Register Console provider
$application->register(new ConsoleServiceProvider(), array(
  'console.name' => 'Premiefier',
  'console.version' => '1.0.0',
  'console.project_directory' => __DIR__.'/..'
));

// Return application instance to web/index.php
return $application;
