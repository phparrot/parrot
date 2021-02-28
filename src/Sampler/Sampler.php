<?php

namespace PHParrot\Parrot\Sampler;

interface Sampler
{
    public function getName(): string;

    // make this protected, and probably move everything onto BaseSampler
    // make BaseSampler implement this interface instead
    public function getRows(): \Generator;
}
