<?php

namespace PHParrot\Parrot\Cleaner\FieldCleaner;

use PHParrot\Parrot\Cleaner\FieldCleaner;

class EmptyString implements FieldCleaner
{
    public function clean(array $parameters, ?string $originalValue = null)
    {
        return '';
    }
}
