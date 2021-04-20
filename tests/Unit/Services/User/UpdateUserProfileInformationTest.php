<?php

namespace Tests\Unit\Services\User;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Validation\ValidationException;
use App\Services\User\UpdateUserProfileInformation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateUserProfileInformationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_update_the_profile(): void
    {
        $user = User::factory()->create();

        $request = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'test@test.com',
        ];

        (new UpdateUserProfileInformation)->update($user, $request);

        $this->assertDatabaseHas('users', [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'test@test.com',
        ]);
    }

    /** @test */
    public function it_keep_validated_mail_if_the_mail_is_not_updated(): void
    {
        $user = User::factory()->create();

        $request = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => $user->email,
        ];

        (new UpdateUserProfileInformation)->update($user, $request);

        $this->assertDatabaseHas('users', [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => $user->email,
        ]);

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    /** @test */
    public function it_fails_if_data_are_wrong(): void
    {
        $user = User::factory()->create();

        $request = [
            'first_name' => 'First name',
            'last_name' => '',
            'email' => '',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateUserProfileInformation)->update($user, $request);
    }

    /** @test */
    public function it_fails_if_email_already_taken(): void
    {
        $user = User::factory()->create();
        User::factory()->create([
            'email' => 'test@test.com',
        ]);

        $request = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'test@test.com',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateUserProfileInformation)->update($user, $request);
    }
}
