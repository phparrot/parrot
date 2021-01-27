<?php

namespace PHParrot\Parrot\Cleaner;

interface FieldCleaner
{
    public function clean(array $parameters, ?string $originalValue);
}
