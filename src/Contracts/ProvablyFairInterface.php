<?php

namespace ProvablyFair\Contracts;

use ProvablyFair\Result;

/**
 * @property SeedInterface $clientSeed
 * @property SeedInterface $serverSeed
 * @property AlgorithmInterface $algorithm
 */
interface ProvablyFairInterface
{
    public function setSystem(SystemInterface $system): self;

    public function getSystem(): SystemInterface;

    /**
     * @return Result[]
     */
    public function generate(int $amount, bool $include_original = false): array;
}
