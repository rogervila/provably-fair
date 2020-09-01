<?php

use PHPUnit\Framework\TestCase;
use ProvablyFair\Algorithm;
use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Seed;
use ProvablyFair\System;

final class SystemTest extends TestCase
{
    const SHA_512 = 'sha512';

    private function createSystem($algorithmString)
    {
        return new System(new Algorithm($algorithmString));
    }
    
    public function test_calculate_expected_result()
    {
        $system = $this->createSystem(self::SHA_512);

        $serverSeed = new Seed('example');
        $clientSeed = new Seed('example');

        $result = $system->calculate($serverSeed, $clientSeed);
        $this->assertEquals($result, 214.0);

        $serverSeed = $system->generateServerSeed($serverSeed);

        $this->assertEquals($serverSeed->getValue(), '3bb12eda3c298db5de25597f54d924f2e17e78a26ad8953ed8218ee682f0bbbe9021e2f3009d152c911bf1f25ec683a902714166767afbd8e5bd0fb0124ecb8a');
    }

    public function test_generate_server_seed_returns_seed_interface()
    {
        $system = $this->createSystem(self::SHA_512);

        $result = $system->generateServerSeed(new Seed(uniqid()));

        $this->assertTrue($result instanceof SeedInterface);
    }

    public function test_calculate_returns_float()
    {
        $system = $this->createSystem(self::SHA_512);

        $result = $system->calculate(new Seed(uniqid()), new Seed(uniqid()));

        $this->assertTrue(is_float($result));
    }

    public function test_calculate_can_return_zero()
    {
        $system = $this->createSystem(self::SHA_512);

        $serverSeed = new Seed('59f159c41fded24cfe3700b88daf1f6142db6d1b7539fc86e86a9c64a46a8f994dae5486a9ede3792a6fbb35fab024285249602222f3723a973d6d99c00a7c91');
        $clientSeed = new Seed('example');

        $result = $system->calculate($serverSeed, $clientSeed);

        $this->assertEquals($result, 0);
    }
}
