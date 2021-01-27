<?php

namespace PHParrot\Parrot\Tests\Cleaner\FieldCleaner;

use Faker\Factory;
use PHParrot\Parrot\Cleaner\FieldCleaner\EmailAddress;
use PHPUnit\Framework\TestCase;

class EmailAddressTest extends TestCase
{
    public function testItReturnsAnEmailAddress(): void
    {
        $cleaner = new EmailAddress(Factory::create());

        $this->assertMatchesRegularExpression("/.+?\@.+?$/", $cleaner->clean([]));
    }
}
