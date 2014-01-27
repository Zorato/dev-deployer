<?php

namespace Deployer\Destinations;

use Gitwrapper\GitWrapper;

use Deployer\Interpolator;
use Deployer\Config\Project;
use Deployer\Sources\Source;

class Local implements Destination
{
    protected $git;
    protected $project;
    protected $interpolator;

    protected $repository;

    public function __construct(GitWrapper $git, Project $project, Interpolator $interpolator)
    {
        $this->git = $git;
        $this->project = $project;
        $this->interpolator = $interpolator;
    }

    public function deploy(Source $source)
    {
        $this->preDeploy();

        $this->setPrivateKey();

        $this->repository = $this->git->workingCopy($this->getPath());
        $this->checkoutCommit($source->getCommit());

        $this->postDeploy();
    }

    protected function getPath()
    {
        $path = $this->interpolator->interpolate($this->project->getPath());
        $this->interpolator->setPath($path);

        return $path;
    }

    protected function setPrivateKey()
    {
        if ($this->project->requiresPrivateKey()) {
            $this->git->setPrivateKey($this->project->getPrivateKeyPath());
        }
    }

    protected function checkoutCommit($commit)
    {
        if ($this->repository->isCloned()) {
            $this->repository->reset(array('hard' => true));
            $this->repository->pull();
        } else {
            $this->repository->clone(
                $this->project->getRepository(),
                $this->project->getFlags()
            );
        }

        $this->repository->checkout($commit);
    }

    protected function preDeploy()
    {
        foreach ($this->project->getPreCommands() as $command) {
            exec($this->interpolator->interpolate($command));
        }
    }

    protected function postDeploy()
    {
        foreach ($this->project->getPostCommands() as $command) {
            exec($this->interpolator->interpolate($command));
        }
    }
}
