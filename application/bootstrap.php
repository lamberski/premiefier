<?php

// Initialize PSR-4 autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Include used classes
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Knp\Provider\ConsoleServiceProvider;
use BitolaCo\Silex\CapsuleServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;

// Initialize application
$application = new Application();

// Load configuration
$config = require __DIR__ . '/config.php';
foreach ($config as $key => $value) {
    $application['config.' . $key] = $value;
}

// Turn debug mode on/off
$application['debug'] = $application['config.debug'];

// Register Twig provider
$application->register(new TwigServiceProvider(), [
    'twig.path'    => __DIR__ . '/../application/views',
    'twig.options' => [
        'strict_variables' => false,
    ],
]);

// Register Laravel Eloquent ORM provider
$application->register(new CapsuleServiceProvider(), [
    'capsule.connection' => [
        'driver'   => 'sqlite',
        'database' => $application['config.db_path'],
    ],
]);

// Register Console provider
$application->register(new ConsoleServiceProvider(), [
    'console.name'              => 'Premiefier',
    'console.version'           => '1.0.0',
    'console.project_directory' => __DIR__ . '/..',
]);

// Register SwiftMailer provider
$application->register(new SwiftmailerServiceProvider());
$application['swiftmailer.use_spool'] = false;
$application['swiftmailer.options']   = [
    'host'       => $application['config.mail_host'],
    'port'       => $application['config.mail_port'],
    'username'   => $application['config.mail_username'],
    'password'   => $application['config.mail_password'],
    'encryption' => $application['config.mail_encryption'],
    'auth_mode'  => $application['config.mail_auth_mode'],
];

// Declare common error handler for all exceptions
$application->error(function (\Exception $exception) use ($application) {
    $code    = $exception instanceof HttpException ? $exception->getStatusCode() : 500;
    $message = $application['debug'] ? $exception->getMessage() : 'Something went wrong.';

    return $application->json([
        'error'  => $message,
        'params' => array_merge(
            $application['request']->query->all(),
            $application['request']->request->all()
        ),
    ], $code);
});

// Define public routes
$namespace = 'Premiefier\Controllers\\';
$application->get('/', $namespace . 'Pages::subscribe');
$application->get('/unsubscribe', $namespace . 'Pages::unsubscribe');
$application->get('/api/movies', $namespace . 'Movies::index');
$application->get('/api/notifications', $namespace . 'Notifications::index');
$application->post('/api/notifications', $namespace . 'Notifications::create');
$application->delete('/api/notifications', $namespace . 'Notifications::delete');
$application->match('/{slug}', $namespace . 'Pages::error404');

// Return application instance to web/index.php
return $application;
