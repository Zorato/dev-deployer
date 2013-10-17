<?php

namespace Deployer\Sources;

interface Source
{
    public function getCommit();
    public function getName();
    public function getUrl();
    public function getBranch();
}
