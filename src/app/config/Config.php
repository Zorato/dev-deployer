<?php

namespace Deployer\Config;

class Config
{
    protected $projects;

    public function addProject(Project $project)
    {
        $this->projects[$project->getName()][$project->getBranch()] = $project;
    }

    public function hasProject($name, $branch)
    {
        return !empty($this->projects[$name][$branch]);
    }

    public function getProject($name, $branch)
    {
        return !empty($this->projects[$name][$branch]) ? $this->projects[$name][$branch] : null;
    }
}
