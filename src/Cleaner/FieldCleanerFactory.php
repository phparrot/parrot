<?php


namespace PHParrot\Parrot\Cleaner;

use Faker\Factory;
use PHParrot\Parrot\Cleaner\FieldCleaner\DateTime;
use PHParrot\Parrot\Cleaner\FieldCleaner\EmailAddress;
use PHParrot\Parrot\Cleaner\FieldCleaner\EmptyString;
use PHParrot\Parrot\Cleaner\FieldCleaner\Faker;
use PHParrot\Parrot\Cleaner\FieldCleaner\FullName;
use PHParrot\Parrot\Cleaner\FieldCleaner\LoremIpsum;
use PHParrot\Parrot\Cleaner\FieldCleaner\NullCleaner;
use PHParrot\Parrot\Cleaner\FieldCleaner\RandomDigits;
use PHParrot\Parrot\Cleaner\FieldCleaner\User;
use PHParrot\Parrot\Cleaner\FieldCleaner\Zero;

class FieldCleanerFactory
{
    public const CLEANERS = [
        'fakefullname' => FullName::class,
        'fakeemail' => EmailAddress::class,
        'fakeuser' => User::class,
        'faker' => Faker::class,
        'zero' => Zero::class,
        'null' => NullCleaner::class,
        'ipsum' => LoremIpsum::class,
        'emptystring' => EmptyString::class,
        'datetime' => DateTime::class,
        'randomdigits' => RandomDigits::class,
    ];

    public static function getCleaner(CleanerConfig $cleanerConfig): FieldCleaner
    {
        if (false === \array_key_exists($cleanerConfig->getName(), self::CLEANERS)) {
            throw new \RuntimeException('Configured cleaner is not valid!');
        }

        $cleanerClass = self::CLEANERS[$cleanerConfig->getName()];

        return new $cleanerClass(Factory::create('en_GB'));
    }
}
