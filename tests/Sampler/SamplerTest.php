<?php

namespace PHParrot\Parrot\Tests\Sampler;

use Doctrine\DBAL\DriverManager;
use PHParrot\Parrot\Database\SourceDatabase;
use PHPUnit\Framework\TestCase;

abstract class SamplerTest extends TestCase
{
    /**
     * @var SourceDatabase
     */
    protected $source;

    protected function setUp(): void
    {
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true
        ]);

        $sql = explode(';', file_get_contents(__DIR__ . '/../fixtures/small-source.sql'));

        foreach ($sql as $command) {
            if (trim($command)) {
                $connection->executeStatement($command);
            }
        }

        $this->source = new SourceDatabase($connection);
    }
}