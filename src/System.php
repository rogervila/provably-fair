<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\AlgorithmInterface;
use ProvablyFair\Contracts\SeedInterface;
use ProvablyFair\Contracts\SystemInterface;

class System implements SystemInterface
{
    /**
     * @var \ProvablyFair\Contracts\AlgorithmInterface
     */
    private $algorithm;

    /**
     * @param \ProvablyFair\Contracts\AlgorithmInterface $algorithm
     */
    public function __construct(AlgorithmInterface $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @param  \ProvablyFair\Contracts\SeedInterface $seed
     *
     * @return \ProvablyFair\Contracts\SeedInterface
     */
    public function generateServerSeed(SeedInterface $seed): SeedInterface
    {
        $class = get_class($seed);

        $hash = hash($this->algorithm->getValue(), $seed->getValue());

        return new $class($hash);
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return string
     */
    private function createHmac(string $key, string $value): string
    {
        return hash_hmac($this->algorithm->getValue(), $value, $key);
    }

    /**
     * @param string $hash
     * @param int $mod
     *
     * @return bool
     */
    private static function divisible(string $hash, int $mod): bool
    {
        /*  We will read in 4 hex at a time, but the first chunk might be a bit smaller
            so ABCDEFGHIJ should be chunked like  AB CDEF GHIJ */
        $value = 0;

        $hash_length = strlen($hash);
        $hash_mod = $hash_length % 4;
        $index = $hash_mod > 0 ? $hash_mod - 4 : 0;

        for ($index; $index < $hash_length; $index += 4) {
            $value = (($value << 16) + intval(substr($hash, $index, $index + 4), 16)) % $mod;
        }

        return $value == 0;
    }

    /**
     * @param \ProvablyFair\Contracts\SeedInterface $serverSeed
     * @param \ProvablyFair\Contracts\SeedInterface $clientSeed
     *
     * @return float
     */
    public function calculate(SeedInterface $serverSeed, SeedInterface $clientSeed): float
    {
        $hash = $this->createHmac($serverSeed->getValue(), $clientSeed->getValue());

        /* In 1 of 101 result is 0. */
        if ($this->divisible($hash, 101)) {
            return 0;
        }

        /* Use the most significant 52-bit from the hash to calculate the result */
        $hash_integer = intval(substr($hash, 0, 52 / 4), 16);
        $exp = pow(2, 52);

        return floor((100 * $exp - $hash_integer) / ($exp - $hash_integer));
    }
}
