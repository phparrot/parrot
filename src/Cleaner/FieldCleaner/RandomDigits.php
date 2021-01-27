<?php

namespace PHParrot\Parrot\Cleaner\FieldCleaner;

use PHParrot\Parrot\Cleaner\FieldCleaner;

class RandomDigits implements FieldCleaner
{
    public function clean(array $parameters, ?string $originalValue = null)
    {
        $digits = empty($parameters[0]) ? 5 : $parameters[0];
        return implode(
            '',
            array_map(
                function () {
                    return mt_rand(0, 9);
                },
                range(1, $digits)
            )
        );
    }
}
