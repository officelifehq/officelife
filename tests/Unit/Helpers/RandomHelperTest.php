<?php

namespace Tests\Unit\Helpers;

use App\Helpers\RandomHelper;
use Tests\TestCase;

class RandomHelperTest extends TestCase
{
    /** @test */
    public function it_returns_a_random_and_hopefully_unique_number(): void
    {
        $number = RandomHelper::getNumber();
        $this->assertIsInt($number);
    }
}
