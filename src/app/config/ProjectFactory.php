<?php

namespace Deployer\Config;

class ProjectFactory
{
    protected $allowedKeys = array(
        'name',
        'branch',
        'repository',
        'path',
        'flags',
        'remote',
        'pre-deploy',
        'post-deploy',
        'private-key'
    );

    public function create(array $projectConfig)
    {
        $projectConfig = $this->populateEmptyKeys($projectConfig);

        $project = $this->createBasicProject($projectConfig);

        $project->setPreCommands($this->getRunList($projectConfig['pre-deploy']));
        $project->setPostCommands($this->getRunList($projectConfig['post-deploy']));
        $project->setFlags($projectConfig['flags']);
        $project->setPrivateKeyPath($projectConfig['private-key']);

        return $project;
    }

    protected function populateEmptyKeys($projectConfig)
    {
        return array_merge(
            array_fill_keys($this->allowedKeys, null),
            $projectConfig
        );
    }

    protected function createBasicProject($projectConfig)
    {
        return new Project(
            $projectConfig['name'],
            $projectConfig['branch'],
            $projectConfig['repository'],
            $projectConfig['path'],
            $projectConfig['remote']
        );
    }

    protected function getRunList($commands)
    {
        $runList = new RunList();
        foreach ($commands as $command) {
            $runList->addCommand(new Command($command));
        }

        return $runList;
    }
}
