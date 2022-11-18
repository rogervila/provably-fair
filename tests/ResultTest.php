<?php

namespace ProvablyFairTests;

use Exception;
use PHPUnit\Framework\TestCase;
use ProvablyFair\Contracts\ProvablyFairInterface;
use ProvablyFair\Result;

final class ResultTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test_readonly_properties(): void
    {
        $result = new Result(
            $provablyFair = $this->createMock(ProvablyFairInterface::class),
            $index = random_int(0, 10),
            $hash = uniqid(),
            $value = uniqid(),
        );

        $this->assertEquals($result->provablyFair, $provablyFair);
        $this->assertEquals($result->index, $index);
        $this->assertEquals($result->hash, $hash);
        $this->assertEquals($result->value, $value);
    }
}
