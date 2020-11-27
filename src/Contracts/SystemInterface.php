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
     * @param SeedInterface $serverSeed
     * @param SeedInterface $clientSeed
     *
     * @return float
     */
    public function calculate(SeedInterface $serverSeed, SeedInterface $clientSeed) : float;
}
