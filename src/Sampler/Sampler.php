<?php


namespace PHParrot\Parrot\Sampler;

interface Sampler
{
    public function getName(): string;

    public function getRows(): array;
}
