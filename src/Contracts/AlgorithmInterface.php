<?php

namespace ProvablyFair\Contracts;

interface AlgorithmInterface
{
    public function __construct(
        string $value,
        ?array $available_algorithms = null,
    );
}
