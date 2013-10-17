<?php

namespace Deployer\Sources;

class Github extends AbstractSource implements Source
{
    protected $payload;

    public function __construct($payload)
    {
        $this->parse($payload);
    }

    protected function parse($payload)
    {
        $this->payload = json_decode($payload);

        $this->commit = $this->payload->after;
        $this->name = $this->payload->repository->name;
        $this->url = $this->payload->repository->url;
        $this->branch = str_replace('refs/heads/', '', $this->payload->ref);
    }
}
