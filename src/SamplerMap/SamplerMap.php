<?php


namespace PHParrot\Parrot\SamplerMap;

use PHParrot\Parrot\Sampler\AllRows;
use PHParrot\Parrot\Sampler\None;
use PHParrot\Parrot\Sampler\MatchedRows;
use PHParrot\Parrot\Sampler\NewestById;

class SamplerMap
{
    public const MAP = [
        'all' => AllRows::class,
        'none' => None::class,
        'matched' => MatchedRows::class,
        'newestbyid' => NewestById::class,
    ];
}
