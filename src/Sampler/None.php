<?php

namespace PHParrot\Parrot\Sampler;

use PHParrot\Parrot\BaseSampler;

/**
 * Essentially a 'no-op' table sampler - allows tables to be specified as required without copying any data
 */
class None extends BaseSampler implements Sampler
{
    public function getName(): string
    {
        return 'None';
    }

    public function getRows(): array
    {
        return [];
    }
}
