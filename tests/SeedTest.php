<?php

namespace ProvablyFairTests;

use PHPUnit\Framework\TestCase;
use ProvablyFair\Exceptions\InvalidSeedException;
use ProvablyFair\Seed;

final class SeedTest extends TestCase
{
    /**
     * @throws InvalidSeedException
     */
    public function test_value(): void
    {
        $seed = new Seed($value = uniqid());
        $this->assertEquals($seed->value, $value);
    }

    public function test_fails_if_seed_is_not_valid(): void
    {
        $this->expectException(InvalidSeedException::class);

        new Seed('');
    }
}
