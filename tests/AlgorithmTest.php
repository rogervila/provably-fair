<?php

namespace ProvablyFairTests;

use PHPUnit\Framework\TestCase;
use ProvablyFair\Algorithm;
use ProvablyFair\Exceptions\InvalidAlgorithmException;

final class AlgorithmTest extends TestCase
{
    /**
     * @throws InvalidAlgorithmException
     */
    public function test_property(): void
    {
        $algorithms = hash_hmac_algos();

        $algorithm = new Algorithm($value = $algorithms[array_rand($algorithms)]);
        $this->assertEquals($algorithm->value, $value);
    }

    /**
     * @throws InvalidAlgorithmException
     */
    public function test_valid_from_list(): void
    {
        $this->assertInstanceOf(Algorithm::class, new Algorithm('sha1', ['sha1']));
    }

    public function test_fails_if_algorithm_is_not_valid(): void
    {
        $this->expectException(InvalidAlgorithmException::class);

        new Algorithm(uniqid());
    }

    public function test_fails_if_algorithm_is_not_listed(): void
    {
        $this->expectException(InvalidAlgorithmException::class);

        new Algorithm(uniqid(), ['foo']);
    }
}
