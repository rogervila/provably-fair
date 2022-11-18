<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\AlgorithmInterface;
use ProvablyFair\Contracts\ProvablyFairInterface;
use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Contracts\SystemInterface;

class ProvablyFair implements ProvablyFairInterface
{
    protected SystemInterface $system;

    public function __construct(
        public readonly SeedInterface $clientSeed,
        public readonly SeedInterface $serverSeed,
        public readonly AlgorithmInterface $algorithm,
    ) {
        $this->setSystem(new System($this->algorithm));
    }

    public function setSystem(SystemInterface $system): self
    {
        $this->system = $system;
        return $this;
    }

    public function getSystem(): SystemInterface
    {
        return $this->system;
    }

    /**
     * @return Result[]
     */
    public function generate(int $amount, bool $include_original = false): array
    {
        $serverSeed = $this->serverSeed;
        $results = $include_original
            ? [new Result(
                $this,
                0,
                $serverSeed->value,
                $this->system->calculate($serverSeed, $this->clientSeed),
            )]
            : [];

        for ($i = $include_original ? 1 : 0; $i < $amount; $i++) {
            $serverSeed = $this->system->generateServerSeed($serverSeed);
            $results[] = new Result(
                $this,
                $i,
                $serverSeed->value,
                $this->system->calculate($serverSeed, $this->clientSeed)
            );
        }

        return $results;
    }
}
