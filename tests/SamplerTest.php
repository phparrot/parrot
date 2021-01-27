<?php

namespace PHParrot\Parrot\Tests;

use PHParrot\Parrot\ReferenceStore;
use PHParrot\Parrot\Sampler\AllRows;
use PHParrot\Parrot\Sampler\None;
use PHParrot\Parrot\Sampler\MatchedRows;
use PHParrot\Parrot\Sampler\Sampler;

class SamplerTest extends SqliteBasedTestCase
{
    public function testEmptySampler(): void
    {
        $sampler = new None(
            (object)[],
            new ReferenceStore(),
            $this->source,
            'test_table_name'
        );
        $this->assertSame([], $sampler->getRows());
    }

    public function testCopyAllSampler(): void
    {
        $sampler = new AllRows(
            (object)[],
            new ReferenceStore(),
            $this->source,
            'fruits'
        );
        $this->assertCount(4, $sampler->getRows());
    }

    public function testCopyAllWithReferenceStore(): void
    {
        $referenceStore = new ReferenceStore();

        $sampler = new AllRows(
            (object)['remember' => ['id' => 'fruit_ids']],
            $referenceStore,
            $this->source,
            'fruits'
        );

        $sampler->execute();

        $this->assertCount(4, $referenceStore->getReferencesByName('fruit_ids'));
    }

    private function generateMatched($config): Sampler
    {
        return new MatchedRows(
            $config,
            new ReferenceStore(),
            $this->source,
            'fruit_x_basket'
        );
    }

    public function testMatchedWithWhereClause(): void
    {
        $config = [
            'constraints' => ['fruit_id' => 1],
            'where' => ['basket_id > 1']
        ];
        $sampler = $this->generateMatched((object)$config);
        $sampler->execute();

        $this->assertCount(2, $sampler->getRows());
    }

    public function testMatchedWhereNoConstraints(): void
    {
        $config = [
            'where' => ['basket_id > 1']
        ];
        $sampler = $this->generateMatched((object)$config);
        $sampler->execute();
        $this->assertCount(4, $sampler->getRows());
    }

    public function testMatchedNoConfigThrows(): void
    {
        $sampler = $this->generateMatched((object)[]);
        self::expectException(\RuntimeException::class);
        $sampler->execute();
    }
}
