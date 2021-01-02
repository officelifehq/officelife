<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VerifyEmailOfLastCreatedEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_marks_the_last_created_employee_as_verified(): void
    {
        User::factory()->create();

        $this->artisan('setup:verify-email');

        $this->assertNotNull(User::latest()->first()->email_verified_at);
    }
}
