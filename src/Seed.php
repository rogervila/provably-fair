<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Exceptions\InvalidSeedException;
use ProvablyFair\Traits\HasValue;

class Seed implements SeedInterface
{
    use HasValue;

    /**
     * @var int
     */
    const MINIMUM_LENGTH = 1;

    /**
     * @param string $value
     *
     * @throws InvalidSeedException
     */
    public function __construct(string $value)
    {
        $this->setValue(trim($value));

        $this->assertSeedIsValid();
    }

    /**
     * @throws InvalidSeedException
     *
     * @return void
     */
    private function assertSeedIsValid()
    {
        if (strlen($this->value) < self::MINIMUM_LENGTH) {
            throw new InvalidSeedException($this->value . ' length should be at least ' . self::MINIMUM_LENGTH);
        }
    }
}
