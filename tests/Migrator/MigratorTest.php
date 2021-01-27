<?php

namespace PHParrot\Parrot\Tests\Migrator;

use Psr\Log\LoggerInterface;
use PHParrot\Parrot\Collection\TableCollection;
use PHParrot\Parrot\Collection\ViewCollection;
use PHParrot\Parrot\Configuration\MigrationConfiguration;
use PHParrot\Parrot\Migrator\Migrator;
use PHParrot\Parrot\Tests\SqliteBasedTestCase;
use PHParrot\Parrot\ReferenceStore;

class MigratorTest extends SqliteBasedTestCase
{
    public function testItThrowsAnExceptionWithAnUnknownSampler(): void
    {
        $fruits = [
            "sampler" => "invalidsampler",
        ];

        $config = MigrationConfiguration::fromJson(\json_encode([
            'name' => 'test-migration',
            "tables" => [
                "fruits" => $fruits
            ]
        ]));

        $tableCollection = TableCollection::fromConfig($config);
        $viewCollection = ViewCollection::fromConfig($config);

        $logger = $this->createMock(LoggerInterface::class);


        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unrecognised sampler type \'invalidsampler\' required');
        $migrator = new Migrator($this->source, $this->destination, $logger);
        $migrator->execute('test-migration', $tableCollection, $viewCollection);
    }
}
