<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Exceptions\InvalidSeedException;

class Seed implements SeedInterface
{
    public const MINIMUM_LENGTH = 1;

    /**
     * @throws InvalidSeedException
     */
    public function __construct(public readonly string $value)
    {
        if (strlen($this->value) < self::MINIMUM_LENGTH) {
            throw new InvalidSeedException($this->value . ' length should be at least ' . self::MINIMUM_LENGTH);
        }
    }
}
