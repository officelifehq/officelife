<?php

namespace App\Helpers;

class RandomHelper
{
    /**
     * Generate a random number, large enough.
     */
    public static function getNumber(): int
    {
        return random_int(10000000, 10000000000);
    }
}
