<?php

namespace ProvablyFair\Contracts;

/**
 * @property string $value
 */
interface AlgorithmInterface
{
    public function __construct(
        string $value,
        ?array $available_algorithms = null,
    );
}
