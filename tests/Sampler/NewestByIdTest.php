<?php

namespace PHParrot\Parrot\Tests\Sampler;

use PHParrot\Parrot\ReferenceStore;
use PHParrot\Parrot\Sampler\Exception\RequiredConfigurationValueNotProvided;
use PHParrot\Parrot\Sampler\Exception\TableNotFound;
use PHParrot\Parrot\Sampler\NewestById;

class NewestByIdTest extends SamplerTest
{
    public function testRowsAreFiltered(): void
    {
        $config = new \stdClass();
        $config->quantity = 1;
        $config->idField = 'id';

        $sampler = new NewestById(
            $config,
            new ReferenceStore(),
            $this->source,
            'fruits'
        );

        $this->assertEquals([[
            'id' => 4,
            'name' => 'cherry'
        ]], $sampler->getRows());
    }

    public function testAnExceptionIsThrownIfTableDoesNotExist(): void
    {
        $config = new \stdClass();
        $config->quantity = 1;
        $config->idField = 'id';

        $sampler = new NewestById(
            $config,
            new ReferenceStore(),
            $this->source,
            'TABLE_THAT_DOES_NOT_EXIST'
        );

        $this->expectException(TableNotFound::class);
        $this->expectExceptionMessage('Table TABLE_THAT_DOES_NOT_EXIST does not exist');
        $sampler->getRows();
    }

    public function testAnExceptionIsThrownIfQuantityParameterIsNotProvided(): void
    {
        $config = new \stdClass();
        $config->idField = 'id';

        $sampler = new NewestById(
            $config,
            new ReferenceStore(),
            $this->source,
            'fruits'
        );

        $this->expectException(RequiredConfigurationValueNotProvided::class);
        $this->expectExceptionMessage('The required parameter \'quantity\' was not provided');
        $sampler->getRows();
    }

    public function testAnExceptionIsThrownIfIdFieldParameterIsNotProvided(): void
    {
        $config = new \stdClass();
        $config->quantity = 1;

        $sampler = new NewestById(
            $config,
            new ReferenceStore(),
            $this->source,
            'fruits'
        );

        $this->expectException(RequiredConfigurationValueNotProvided::class);
        $this->expectExceptionMessage('The required parameter \'idField\' was not provided');
        $sampler->getRows();
    }
}
