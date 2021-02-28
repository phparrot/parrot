<?php

namespace PHParrot\Parrot\Sampler;

use PHParrot\Parrot\BaseSampler;

/**
 * Essentially a 'no-op' table sampler - allows tables to be specified as required without copying any data
 */
class None extends BaseSampler
{
    public function getName(): string
    {
        return 'None';
    }

    public function fetchData(): \Generator
    {
        foreach([] as $value) {
            yield $value;
        }
    }
}
