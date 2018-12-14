<?php

use PHPUnit\Framework\TestCase;
use ProvablyFair\Seed;
use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Exceptions\InvalidSeedException;

final class SeedTest extends TestCase
{
    public function test_fails_if_seed_is_not_valid()
    {
        $this->expectException(InvalidSeedException::class);

        new Seed('');
    }
}
