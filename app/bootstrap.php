<?php

// Initialize PSR-4 autoload
require_once __DIR__.'/../vendor/autoload.php';

// Include used classes
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use BitolaCo\Silex\CapsuleServiceProvider;
use Knp\Provider\ConsoleServiceProvider;

// Init application
$app = new Application();
$app['debug'] = true;

// Register Twig provider
$app->register(new TwigServiceProvider(), [
  'twig.path' => __DIR__.'/../app/views',
  'twig.options' => [
    'strict_variables' => false,
  ],
]);

// Register Laravel Eloquent ORM provider
$app->register(new CapsuleServiceProvider(), [
 'capsule.connection' => [
    'driver' => 'sqlite',
    'database' => __DIR__.'/../'.getenv('DB_PATH'),
  ]
]);

// Return application instance to web/index.php
return $app;
