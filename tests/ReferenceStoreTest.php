<?php

namespace PHParrot\Parrot\Tests;

use PHPUnit\Framework\TestCase;
use PHParrot\Parrot\ReferenceStore;

class ReferenceStoreTest extends TestCase
{
    public function testBasicFunctions(): void
    {
        $store = new ReferenceStore();
        $primes = [1, 3, 5, 7];
        $store->setReferencesByName('primes', $primes);
        $this->assertEquals($primes, $store->getReferencesByName('primes'));
        $this->assertEquals([], $store->getReferencesByName('nosuch'));
    }
}
