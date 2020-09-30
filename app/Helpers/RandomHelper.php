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
        $number = rand(10000000, 10000000000);
        return $number;
    }
}
