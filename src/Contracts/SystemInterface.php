<?php

namespace ProvablyFair\Contracts;

/**
 * @property string $algorithm
 */
interface SystemInterface
{
    public function generateServerSeed(SeedInterface $seed): SeedInterface;

    public function calculate(SeedInterface $serverSeed, SeedInterface $clientSeed): float;
}
