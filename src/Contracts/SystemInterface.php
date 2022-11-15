<?php

namespace ProvablyFair\Contracts;

interface SystemInterface
{
    public function generateServerSeed(SeedInterface $seed) : SeedInterface;

    public function calculate(SeedInterface $serverSeed, SeedInterface $clientSeed) : float;
}
