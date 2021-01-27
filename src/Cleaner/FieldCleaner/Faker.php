<?php

namespace PHParrot\Parrot\Cleaner\FieldCleaner;

use Faker\Generator;
use PHParrot\Parrot\Cleaner\FieldCleaner;

class Faker implements FieldCleaner
{
    /**
     * @var Generator
     */
    private $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function clean(array $parameters, ?string $originalValue = null)
    {
        $fakerField = $parameters[0];
        try {
            $this->faker->getFormatter($fakerField);

            return $this->faker->$fakerField;
        } catch (\InvalidArgumentException $e) {
            throw new \RuntimeException("Faker does not support '$fakerField'");
        }
    }
}
