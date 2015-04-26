<?php

// Initialize PSR-4 autoload
require_once __DIR__.'/../vendor/autoload.php';

// Include used classes
use Silex\Application;
use Igorw\Silex\ConfigServiceProvider;
use Silex\Provider\TwigServiceProvider;
use BitolaCo\Silex\CapsuleServiceProvider;
use Knp\Provider\ConsoleServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;

// Initialize application
$application = new Application();

// Register Configuration provider
$application->register(new ConfigServiceProvider(__DIR__.'/configuration.php'));

// Register Twig provider
$application->register(new TwigServiceProvider(), [
  'twig.path' => __DIR__.'/../application/views',
  'twig.options' => [
    'strict_variables' => false,
  ],
]);

// Register Laravel Eloquent ORM provider
$application->register(new CapsuleServiceProvider(), [
 'capsule.connection' => [
    'driver' => 'sqlite',
    'database' => __DIR__.'/../'.$application['db_path'],
  ]
]);

// Register Console provider
$application->register(new ConsoleServiceProvider(), [
  'console.name' => 'Premiefier',
  'console.version' => '1.0.0',
  'console.project_directory' => __DIR__.'/..',
]);

// Require SwiftMailer provider
$application->register(new SwiftmailerServiceProvider([
  'swiftmailer.use_spool' => false,
  'swiftmailer.transport' => function ($application) {
    return \Swift_MailTransport::newInstance();
  },
]));

// Return application instance to web/index.php
return $application;
