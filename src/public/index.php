<?php

require '../bootstrap.php';

// Prepare app
$app = new \Slim\Slim();

// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    $app->config('debug', true);
});

// Create monolog logger
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('deployer');
    $log->pushHandler(new \Monolog\Handler\StreamHandler(
        '../../logs/app.log',
        \Psr\Log\LogLevel::DEBUG
    ));
    return $log;
});

// create git wrapper
$app->container->singleton('git', function () use ($app) {
    $git = new \GitWrapper\GitWrapper();
    $git->setEnvVar('HOME', HOME_PATH);
    return $git;
});

$app->container->singleton('projectFactory', function () use ($app) {
    return new \Deployer\Config\ProjectFactory();
});

// create project config wrapper
$app->container->singleton('projects', function () use ($app) {
    $config = new Deployer\Config\Config();
    $projects = require APPLICATION_PATH . '/config/projects.php';
    foreach ($projects as &$project) {
        $config->addProject($app->projectFactory->create($project));
    }

    return $config;
});

require APPLICATION_PATH . '/src/app/routes/github.php';

// Run app
$app->run();
