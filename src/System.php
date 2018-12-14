<?php

namespace ProvablyFair;

use ProvablyFair\Contracts\SystemInterface;
use ProvablyFair\Contracts\AlgorithmInterface;
use ProvablyFair\Contracts\SeedInterface;

class System implements SystemInterface
{
    /**
     * @var AlgorithmInterface
     */
    private $algorithm;

    /**
     * @param Algorithm $algorithm
     */
    public function __construct(AlgorithmInterface $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @param SeedInterface $seed
     *
     * @return SeedInterface
     */
    public function generateServerSeed(SeedInterface $seed) : SeedInterface
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
    private function createHmac(string $key, string $value) : string
    {
        return hash_hmac($this->algorithm->getValue(), $value, $key);
    }

    /**
     * @param string $hash
     * @param int $mod
     *
     * @return int
     */
    private static function divisible(string $hash, int $mod)
    {
        /*  We will read in 4 hex at a time, but the first chunk might be a bit smaller
            so ABCDEFGHIJ should be chunked like  AB CDEF GHIJ */
        $val = 0;

        $o = strlen($hash) % 4;

        for ($i = $o > 0 ? $o - 4 : 0; $i < strlen($hash); $i += 4) {
            $val = (($val << 16) + intval(substr($hash, $i, $i + 4), 16)) % $mod;
        }

        return $val == 0;
    }

    /**
     * @param string $serverSeed
     * @param string $clientSeed
     *
     * @return float
     */
    public function calculate(SeedInterface $serverSeed, SeedInterface $clientSeed) : float
    {
        $hash = $this->createHmac($serverSeed->getValue(), $clientSeed->getValue());

        /* In 1 of 101 result is 0. */
        if ($this->divisible($hash, 101)) {
            return 0;
        }

        /* Use the most significant 52-bit from the hash to calculate the result */
        $h = intval(substr($hash, 0, 52 / 4), 16);
        $e = pow(2, 52);

        return floor((100 * $e - $h) / ($e - $h));
    }
}
