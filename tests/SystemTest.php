<?php

use PHPUnit\Framework\TestCase;
use ProvablyFair\System;
use ProvablyFair\Algorithm;
use ProvablyFair\Seed;
use ProvablyFair\Contracts\SeedInterface;

final class SystemTest extends TestCase
{
    const SHA_512 = 'sha512';

    private function createSystem($algorithmString)
    {
        return new System(new Algorithm($algorithmString));
    }

    public function test_generate_server_seed_returns_seed_interface() : void
    {
        $system = $this->createSystem(self::SHA_512);

        $result = $system->generateServerSeed(new Seed(uniqid()));

        $this->assertTrue($result instanceof SeedInterface);
    }

    public function test_calculate_returns_float() : void
    {
        $system = $this->createSystem(self::SHA_512);

        $result = $system->calculate(new Seed(uniqid()), new Seed(uniqid()));

        $this->assertTrue(is_float($result));
    }
}
