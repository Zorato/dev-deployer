<?php

namespace Deployer\Sources;

abstract class AbstractSource
{
    protected $commit;
    protected $name;
    protected $url;
    protected $branch;

    public function getCommit()
    {
        return $this->commit;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getBranch()
    {
        return $this->branch;
    }
}
