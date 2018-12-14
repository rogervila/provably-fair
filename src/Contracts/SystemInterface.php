<?php

namespace ProvablyFair\Contracts;

interface SystemInterface
{
    /**
     * @param SeedInterface $seed
     *
     * @return SeedInterface
     */
    public function generateServerSeed(SeedInterface $seed) : SeedInterface;

    /**
     * @param string $serverSeed
     * @param string $clientSeed
     *
     * @return float
     */
    public function calculate(SeedInterface $serverSeed, SeedInterface $clientSeed) : float;
}
