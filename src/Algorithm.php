<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\AlgorithmInterface;
use ProvablyFair\Exceptions\InvalidAlgorithmException;
use ProvablyFair\Traits\HasValue;

class Algorithm implements AlgorithmInterface
{
    use HasValue;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->setValue(trim($value));

        $this->assertAlgorithmIsValid();
    }

    /**
     * @return void
     */
    private function assertAlgorithmIsValid()
    {
        if (!in_array($this->value, hash_algos())) {
            throw new InvalidAlgorithmException($this->value . ' is not a valid algorithm');
        }
    }
}
