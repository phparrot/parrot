<?php

namespace PHParrot\Parrot\Tests\Collection;

use PHParrot\Parrot\Collection\TableCollection;
use PHPUnit\Framework\TestCase;
use PHParrot\Parrot\Configuration\MigrationConfiguration;

class TableCollectionTest extends TestCase
{
    public function testItReturnsTheListOfTables(): void
    {
        $fruits = [
            "sampler" => "matched",
            "constraints" => [
                "name" => [
                    "apple",
                    "pear"
                ]
            ],
            "remember" => [
                "id" => "fruit_ids"
            ]
        ];

        $vegetables = [
            "sampler" => "NewestById",
            "idField" => "id",
            "quantity" => 2
        ];

        $config = MigrationConfiguration::fromJson(\json_encode([
            'name' => 'test-migration',
            "tables" => [
                "fruits" => $fruits,
                "vegetables" => $vegetables,
            ]
        ]));

        $tableCollection = TableCollection::fromConfig($config);

        $this->assertEquals([
            'fruits' => $fruits,
            'vegetables' => $vegetables,
        ], \json_decode(\json_encode($tableCollection->getTables()), true));
    }

    public function testTablesAreNotOptional(): void
    {
        $config = MigrationConfiguration::fromJson(\json_encode([
            'name' => 'test-migration'
        ]));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No table config was defined');
        TableCollection::fromConfig($config);
    }
}
