<?php  namespace Deployer\Sources; 

class Bitbucket extends AbstractSource implements Source{

    protected $payload;

    public function __construct($payload)
    {
        $this->parse($payload);
    }

    protected function parse($payload)
    {
        $this->payload = json_decode($payload);

        $this->name = $this->payload->repository->name;
        $this->url = $this->payload->canon_url.$this->payload->repository->absolute_url;
        $commit = \end($this->payload->commits);
        $this->branch = $commit->branch;
        $this->commit = $commit->raw_node;
    }

} 