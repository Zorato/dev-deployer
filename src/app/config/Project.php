<?php

namespace Deployer\Config;

class Project
{
    protected $name;
    protected $branch;
    protected $repository;
    protected $path;
    protected $flags;
    protected $pre_commands;
    protected $post_commands;

    protected $privateKeyPath;

    public function __construct($name, $branch, $repository, $path)
    {
        $this->name = $name;
        $this->branch = $branch;
        $this->repository = $repository;
        $this->path = $path;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getBranch()
    {
        return $this->branch;
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getPostCommands()
    {
        return $this->post_commands;
    }

    public function setPostCommands(RunList $commands)
    {
        $this->post_commands = $commands;
    }

    public function getPreCommands()
    {
        return $this->pre_commands;
    }

    public function setPreCommands(RunList $commands)
    {
        $this->pre_commands = $commands;
    }

    public function getFlags()
    {
        return $this->flags;
    }

    public function setFlags($flags)
    {
        $this->flags = $flags;
    }

    public function getPrivateKeyPath()
    {
        return $this->privateKeyPath;
    }

    public function setPrivateKeyPath($path)
    {
        $this->privateKeyPath = $path;
    }

    public function requiresPrivateKey()
    {
        return !empty($this->privateKeyPath);
    }
}
