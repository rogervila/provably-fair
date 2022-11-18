<?php

namespace ProvablyFairTests;

use Exception;
use PHPUnit\Framework\TestCase;
use ProvablyFair\Algorithm;
use ProvablyFair\Contracts\AlgorithmInterface;
use ProvablyFair\Contracts\ProvablyFairInterface;
use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Contracts\SystemInterface;
use ProvablyFair\Exceptions\InvalidAlgorithmException;
use ProvablyFair\Exceptions\InvalidSeedException;
use ProvablyFair\ProvablyFair;
use ProvablyFair\Seed;

final class ProvablyFairTest extends TestCase
{
    public function test_properties(): void
    {
        $provablyFair = new ProvablyFair(
            $clientSeed = $this->createMock(SeedInterface::class),
            $serverSeed = $this->createMock(SeedInterface::class),
            $algorithm = $this->createMock(AlgorithmInterface::class),
        );

        $this->assertInstanceOf(ProvablyFairInterface::class, $provablyFair);

        $this->assertEquals($provablyFair->clientSeed, $clientSeed);
        $this->assertEquals($provablyFair->serverSeed, $serverSeed);
        $this->assertEquals($provablyFair->algorithm, $algorithm);

        $this->assertInstanceOf(SystemInterface::class, $provablyFair->getSystem());
        $provablyFair->setSystem($system = $this->createMock(SystemInterface::class));
        $this->assertEquals($system, $provablyFair->getSystem());
    }

    public function test_expected_results(): void
    {
        $provablyFair = new ProvablyFair(
            new Seed('example'),
            new Seed('example'),
            new Algorithm('sha512'),
        );

        $results = $provablyFair->generate($amount = 3);
        $this->assertCount($amount, $results);

        $this->assertEquals(0, $results[0]->index);
        $this->assertEquals('3bb12eda3c298db5de25597f54d924f2e17e78a26ad8953ed8218ee682f0bbbe9021e2f3009d152c911bf1f25ec683a902714166767afbd8e5bd0fb0124ecb8a', $results[0]->hash);
        $this->assertEquals(1201.0, $results[0]->value);

        $this->assertEquals(1, $results[1]->index);
        $this->assertEquals('666122910be45bf57a3092136e92bb95765cf250c8f6df980da7de112b528989fed89fa5e8bb7c13c5a6e6c295bd31eddf706dd63a7a48721003906216764207', $results[1]->hash);
        $this->assertEquals(129.0, $results[1]->value);

        $this->assertEquals(2, $results[2]->index);
        $this->assertEquals('c76f6faacb7bf9a0250155f529a93784656070e97071d56f5044e6d071963fff8e123a2e36da8dbc671d62d89c185281542b848d85abd4fce1809333e060c6eb', $results[2]->hash);
        $this->assertEquals(136.0, $results[2]->value);

        $this->assertArrayNotHasKey(3, $results);
    }

    public function test_expected_results_including_original(): void
    {
        $provablyFair = new ProvablyFair(
            new Seed('example'),
            new Seed('example'),
            new Algorithm('sha512'),
        );

        $results = $provablyFair->generate($amount = 3, true);
        $this->assertCount($amount, $results);

        $this->assertEquals(0, $results[0]->index);
        $this->assertEquals('example', $results[0]->hash);
        $this->assertEquals(214.0, $results[0]->value);

        $this->assertEquals(1, $results[1]->index);
        $this->assertEquals('3bb12eda3c298db5de25597f54d924f2e17e78a26ad8953ed8218ee682f0bbbe9021e2f3009d152c911bf1f25ec683a902714166767afbd8e5bd0fb0124ecb8a', $results[1]->hash);
        $this->assertEquals(1201.0, $results[1]->value);

        $this->assertEquals(2, $results[2]->index);
        $this->assertEquals('666122910be45bf57a3092136e92bb95765cf250c8f6df980da7de112b528989fed89fa5e8bb7c13c5a6e6c295bd31eddf706dd63a7a48721003906216764207', $results[2]->hash);
        $this->assertEquals(129.0, $results[2]->value);

        $this->assertArrayNotHasKey(3, $results);
    }

    /**
     * @throws InvalidAlgorithmException
     * @throws InvalidSeedException
     * @throws Exception
     */
    public function test_random_calculations(): void
    {
        $available_algorithms = hash_hmac_algos();

        foreach ($available_algorithms as $algorithm) {
            $amount = random_int(10, 100);
            $provablyFair = new ProvablyFair(
                new Seed(uniqid()),
                new Seed(uniqid()),
                new Algorithm($algorithm),
            );

            $results = $provablyFair->generate($amount, boolval(random_int(0, 1)));
            $this->assertCount($amount, $results);

            for ($i = 0; $i < $amount; $i++) {
                $this->assertEquals($i, $results[$i]->index);

                if ($i === ($amount - 1)) continue;

                $currentResults = (new ProvablyFair(
                    $results[$i]->provablyFair->clientSeed,
                    new Seed($results[$i]->hash),
                    $results[$i]->provablyFair->algorithm,
                ))->generate(1);

                $this->assertEquals(
                    $currentResults[0]->hash,
                    $results[$i + 1]->hash
                );

                $this->assertEquals(
                    $currentResults[0]->value,
                    $results[$i + 1]->value
                );
            }
        }
    }
}
