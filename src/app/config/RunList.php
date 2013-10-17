<?php

namespace Deployer\Config;

class RunList implements \Iterator
{
    protected $current = 0;
    protected $commands;

    public function addCommand(Command $action)
    {
        $this->commands[] = $action;
    }

    public function rewind() {
        $this->current = 0;
    }

    public function current() {
        return $this->commands[$this->current];
    }

    public function key() {
        return $this->current;
    }

    public function next() {
        ++$this->current;
    }

    public function valid() {
        return isset($this->commands[$this->current]);
    }
}
