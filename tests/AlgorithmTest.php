<?php

use PHPUnit\Framework\TestCase;
use ProvablyFair\Algorithm;
use ProvablyFair\Exceptions\InvalidAlgorithmException;

final class AlgorithmTest extends TestCase
{
    /**
     * @return void
     */
    public function test_fails_if_algorithm_is_not_valid()
    {
        $this->expectException(InvalidAlgorithmException::class);

        new Algorithm(uniqid());
    }
}
