<?php

namespace PHParrot\Parrot\Cleaner\FieldCleaner;

class Zero implements \PHParrot\Parrot\Cleaner\FieldCleaner
{
    public function clean(array $parameters, ?string $originalValue = null)
    {
        return 0;
    }
}
