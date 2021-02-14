<?php

namespace PHParrot\Parrot\Tests\Sampler;

use PHParrot\Parrot\ReferenceStore;
use PHParrot\Parrot\Sampler\Exception\TableNotFound;
use PHParrot\Parrot\Sampler\MatchedRows;

class MatchedRowsTest extends SamplerTest
{
    public function testMatchedRowsAreReturned(): void
    {
        $config = new \stdClass();
        $config->constraints = new \stdClass();

        $config->constraints->name = [
            'apple',
            'pear'
        ];

        $sampler = new MatchedRows(
            $config,
            new ReferenceStore(),
            $this->source,
            'fruits'
        );

        $this->assertEquals(
            [
                [
                    'id' => 2,
                    'name' => 'pear'
                ],
                [
                    'id' => 3,
                    'name' => 'apple'
                ]
            ],
            $sampler->getRows()
        );
    }

    public function testRowsAreMatchedByReferenceConstraint(): void
    {
        $config = new \stdClass();
        $config->constraints = new \stdClass();

        $config->constraints->id = '$fruit_ids';

        $referenceStore = new ReferenceStore();
        $referenceStore->setReferencesByName('fruit_ids', [1, 4]);

        $sampler = new MatchedRows(
            $config,
            $referenceStore,
            $this->source,
            'fruits'
        );

        $this->assertEquals(
            [
                [
                    'id' => 1,
                    'name' => 'banana'
                ],
                [
                    'id' => 4,
                    'name' => 'cherry'
                ]
            ],
            $sampler->getRows()
        );
    }


    public function testRowReferencesAreRemembered(): void
    {
        $config = new \stdClass();
        $config->constraints = new \stdClass();

        $config->remember = new \stdClass();
        $config->remember->id = 'fruit_ids';

        $referenceStore = new ReferenceStore();

        $sampler = new MatchedRows(
            $config,
            $referenceStore,
            $this->source,
            'fruits'
        );

        $sampler->getRows();

        $this->assertSame([
            '1', '2', '3', '4'
        ], $referenceStore->getReferencesByName('fruit_ids'));
    }

    public function testRowsAreFilteredByReference(): void
    {
        $config = new \stdClass();
        $config->constraints = new \stdClass();
        $config->constraints->id = '$fruit_ids';

        $referenceStore = new ReferenceStore();
        $referenceStore->setReferencesByName('fruit_ids', [2, 3]);

        $sampler = new MatchedRows(
            $config,
            $referenceStore,
            $this->source,
            'fruits'
        );

        $results = $sampler->getRows();

        $this->assertEquals([
            [
                'id' => 2,
                'name' => 'pear'
            ],
            [
                'id' => 3,
                'name' => 'apple'
            ],
        ], $results);
    }

    public function testAnExceptionIsThrownIfTableDoesNotExist(): void
    {
        $config = new \stdClass();
        $config->constraints = new \stdClass();
        $config->constraints->id = '$fruit_ids';

        $sampler = new MatchedRows(
            $config,
            new ReferenceStore(),
            $this->source,
            'TABLE_THAT_DOES_NOT_EXIST'
        );

        $this->expectException(TableNotFound::class);
        $this->expectExceptionMessage('Table TABLE_THAT_DOES_NOT_EXIST does not exist');
        $sampler->getRows();
    }
}
