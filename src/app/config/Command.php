<?php

namespace Deployer\Config;

class Command
{
    protected $command;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function __toString()
    {
        return $this->command;
    }
}
