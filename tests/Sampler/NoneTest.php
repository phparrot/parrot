<?php

namespace PHParrot\Parrot\Tests\Sampler;

use Doctrine\DBAL\DriverManager;
use PHParrot\Parrot\Database\SourceDatabase;
use PHParrot\Parrot\ReferenceStore;
use PHParrot\Parrot\Sampler\None;
use PHPUnit\Framework\TestCase;

class NoneTest extends TestCase
{
    public function testNoRowsAreReturned(): void
    {
        $sampler = new None(
            new \stdClass(),
            new ReferenceStore(),
            new SourceDatabase(
                DriverManager::getConnection([
                    'driver' => 'pdo_sqlite',
                    'memory' => true
                ])
            ),
            'TABLE_THAT_DOES_NOT_EXIST'
        );

        $this->assertSame([], iterator_to_array($sampler->getRows()));
    }
}