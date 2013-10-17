<?php

$app->post('/github', function () use ($app) {
    $app->log->debug('Payload: ' . $app->request->post('payload'));

    $source = new \Deployer\Sources\Github($app->request->post('payload'));

    $project = $app->projects->getProject($source->getName(), $source->getBranch());

    $app->log->debug('Deploying: ' . $source->getName() . '(' . $source->getBranch() . ')');

    if (empty($project)) {
        return $app->pass();
    }

    $destination = new \Deployer\Destinations\Local(
        $app->git,
        $project,
        new \Deployer\Interpolator($source)
    );

    try {
        $destination->deploy($source);
    } catch (Exception $e) {
        $app->log->error($e->getMessage());
    }

    echo 'done';
});
