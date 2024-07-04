<?php

namespace Tests\Integration;

use App\Models\User\User;
use Tests\TestCase;

class VerifyEmailOfLastCreatedEmployeeTest extends TestCase
{
    /** @test */
    public function it_marks_the_last_created_employee_as_verified(): void
    {
        User::factory()->create();

        $this->artisan('setup:verify-email');

        $this->assertNotNull(User::latest()->first()->email_verified_at);
    }
}
