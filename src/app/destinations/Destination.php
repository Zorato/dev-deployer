<?php

namespace Deployer\Destinations;

use Deployer\Sources\Source;

interface Destination
{
    public function deploy(Source $source);
}
