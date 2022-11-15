<?php

namespace ProvablyFair;

class Result
{
    public function __construct(
        public readonly ProvablyFair $provablyFair,
        public readonly int $index,
        public readonly string $hash,
        public readonly string $value,
    )
    {
    }
}
