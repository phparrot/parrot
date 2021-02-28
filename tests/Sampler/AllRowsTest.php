<?php

namespace PHParrot\Parrot\Tests\Sampler;

use PHParrot\Parrot\ReferenceStore;
use PHParrot\Parrot\Sampler\AllRows;
use PHParrot\Parrot\Sampler\Exception\TableNotFound;

class AllRowsTest extends SamplerTest
{
    public function testAllRowsAreReturned(): void
    {
        $sampler = new AllRows(
            new \stdClass,
            new ReferenceStore(),
            $this->source,
            'fruits'
        );

        $this->assertSame(4, count(iterator_to_array($sampler->getRows())));
    }

    public function testRowsAreFiltered(): void
    {
        $config = new \stdClass();

        $config->limit = 2;

        $sampler = new AllRows(
            $config,
            new ReferenceStore(),
            $this->source,
            'fruits'
        );

        $this->assertSame(2, count(iterator_to_array($sampler->getRows())));
    }

    public function testAnExceptionIsThrownIfTableDoesNotExist(): void
    {
        $sampler = new AllRows(
            new \stdClass(),
            new ReferenceStore(),
            $this->source,
            'TABLE_THAT_DOES_NOT_EXIST'
        );

        $this->expectException(TableNotFound::class);
        $this->expectExceptionMessage('Table TABLE_THAT_DOES_NOT_EXIST does not exist');
        iterator_to_array($sampler->getRows());
    }
}
