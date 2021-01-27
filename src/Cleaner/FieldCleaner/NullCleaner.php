<?php

namespace PHParrot\Parrot\Cleaner\FieldCleaner;

use PHParrot\Parrot\Cleaner\FieldCleaner;

class NullCleaner implements FieldCleaner
{
    public function clean(array $parameters, ?string $originalValue = null)
    {
        return null;
    }
}
