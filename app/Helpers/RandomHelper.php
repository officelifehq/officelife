<?php

namespace App\Helpers;

class RandomHelper
{
    /**
     * Generate a random number, large enough.
     *
     * @return int
     */
    public static function getNumber(): int
    {
        return random_int(10000000, 10000000000);
    }
}
