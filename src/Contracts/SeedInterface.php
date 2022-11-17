<?php

namespace ProvablyFair\Contracts;

/**
 * @property string $value
 */
interface SeedInterface
{
    public function __construct(string $value);
}
