<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\RandomHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RandomHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_random_and_hopefully_unique_number(): void
    {
        $number = RandomHelper::getNumber();
        $this->assertIsInt($number);
    }
}
