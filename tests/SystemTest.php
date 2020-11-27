<?php

use PHPUnit\Framework\TestCase;
use ProvablyFair\Algorithm;
use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Seed;
use ProvablyFair\System;

final class SystemTest extends TestCase
{
    /**
     * @var string
     */
    const SHA_512 = 'sha512';

    /**
     * @param  string $algorithmString
     *
     * @return \ProvablyFair\System
     */
    private function createSystem($algorithmString)
    {
        return new System(new Algorithm($algorithmString));
    }

    /**
     * @return void
     */
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

    /**
     * @return void
     */
    public function test_generate_server_seed_returns_seed_interface()
    {
        $system = $this->createSystem(self::SHA_512);

        $result = $system->generateServerSeed(new Seed(uniqid()));

        $this->assertTrue($result instanceof SeedInterface);
    }

    /**
     * @return void
     */
    public function test_list_of_results()
    {
        $amount = 5;
        $hashes = [];
        $results = [];
        $system = $this->createSystem(self::SHA_512);

        $serverSeed = new Seed('server');
        $clientSeed = new Seed('client');

        for ($i = 0; $i < $amount; $i++) {
            $serverSeed = $system->generateServerSeed($serverSeed);

            $results[$i] = $system->calculate($serverSeed, $clientSeed);
            $hashes[$i] = $serverSeed->getValue();
        }

        $this->assertEquals($results[0], 143.0);
        $this->assertEquals($hashes[0], 'fb7daafb37d21221f75d0451fa35dfaacfca509150cecad0e40217b593c5b47566e80c1dd1f74556b3c46a357b699c860976372361a99332934e720a586b7786');

        $this->assertEquals($results[1], 123.0);
        $this->assertEquals($hashes[1], '7612641f63691f4ca5ea8c3c57b22bc0904a337a68e625615ad50c931494dc44b3509c7510de6fae5371aba5bf4483f00981f945f2cd6d8568624cd72c9e1f6f');

        $this->assertEquals($results[2], 404.0);
        $this->assertEquals($hashes[2], '2b5b0332935879521cd4584062e22a884409168725d34c04b44747023af70ad04adbf9d7f8ee2ef247b3799641c6faeed8e6271a44fccc10547d7f6a8e0b92f2');

        $this->assertEquals($results[3], 154.0);
        $this->assertEquals($hashes[3], '4ee23247b9bc1b014831bdcc22672aa0a6bc3ab21f7ada00bcdc17f8567db122cfee545651b96f3220071e1cd3965c6da8d82708930b4b27e418215999ffd7aa');

        $this->assertEquals($results[4], 151.0);
        $this->assertEquals($hashes[4], '97597a7da22e4d938535f92cae9d5db77e636ca1e1e52e711c516d13f91398911a79c53b3e96587ecb726a1b5b963ebb7fedb7810bb7e76ecbacee97e227cc47');
    }

    /**
     * @return void
     */
    public function test_calculate_returns_float()
    {
        $system = $this->createSystem(self::SHA_512);

        $result = $system->calculate(new Seed(uniqid()), new Seed(uniqid()));

        $this->assertTrue(is_float($result));
    }

    /**
     * @return void
     */
    public function test_calculate_can_return_zero()
    {
        $system = $this->createSystem(self::SHA_512);

        $serverSeed = new Seed('59f159c41fded24cfe3700b88daf1f6142db6d1b7539fc86e86a9c64a46a8f994dae5486a9ede3792a6fbb35fab024285249602222f3723a973d6d99c00a7c91');
        $clientSeed = new Seed('example');

        $result = $system->calculate($serverSeed, $clientSeed);

        $this->assertEquals($result, 0);
    }
}
