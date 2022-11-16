<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\AlgorithmInterface;
use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Contracts\SystemInterface;

class ProvablyFair
{
    protected SystemInterface $system;

    public function __construct(
        protected readonly SeedInterface $clientSeed,
        protected readonly SeedInterface $serverSeed,
        protected readonly AlgorithmInterface $algorithm,
    ) {
        $this->setSystem(new System($this->algorithm));
    }

    public function setSystem(SystemInterface $system): self
    {
        $this->system = $system;
        return $this;
    }

    /**
     * @return Result[]
     */
    public function generate(int $amount, bool $prepend = false): array
    {
        $serverSeed = $this->serverSeed;
        $results = $prepend
            ? [new Result(
                $this,
                0,
                $serverSeed->value,
                $this->system->calculate($serverSeed, $this->clientSeed),
            )]
            : [];

        for ($i = 0; $i < $amount; $i++) {
            $serverSeed = $this->system->generateServerSeed($serverSeed);
            $results[] = new Result(
                $this,
                $prepend ? ($i + 1) : $i,
                $serverSeed->value,
                $this->system->calculate($serverSeed, $this->clientSeed)
            );
        }

        return $results;
    }
}
