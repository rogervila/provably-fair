<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\ProvablyFairInterface;

class Result
{
    public function __construct(
        public readonly ProvablyFairInterface $provablyFair,
        public readonly int $index,
        public readonly string $hash,
        public readonly string $value,
    ) {
    }
}
