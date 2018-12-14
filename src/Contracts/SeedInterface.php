<?php

namespace ProvablyFair\Contracts;

interface SeedInterface
{
    /**
     * @param string $value
     */
    public function __construct(string $value);

    /**
     * @param mixed $value
     * @return void
     */
    public function setValue($value);

    /**
     * @return mixed
     */
    public function getValue();
}
