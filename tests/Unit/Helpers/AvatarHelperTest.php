<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\AvatarHelper;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AvatarHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_an_avatar(): void
    {
        $michael = Employee::factory()->create();
        $this->assertEquals(
            $michael->avatar,
            AvatarHelper::getImage($michael)
        );
    }
}
