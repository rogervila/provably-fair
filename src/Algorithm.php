<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\AlgorithmInterface;
use ProvablyFair\Exceptions\InvalidAlgorithmException;

class Algorithm implements AlgorithmInterface
{
    /**
     * @throws InvalidAlgorithmException
     */
    public function __construct(
        public readonly string $value,
        ?array $available_algorithms = null,
    ) {
        if (!in_array($this->value, $available_algorithms ?? hash_hmac_algos())) {
            throw new InvalidAlgorithmException($this->value . ' is not a valid algorithm');
        }
    }
}
