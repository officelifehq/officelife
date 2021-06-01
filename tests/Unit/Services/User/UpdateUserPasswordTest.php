<?php

namespace Tests\Unit\Services\User;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use App\Services\User\UpdateUserPassword;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateUserPasswordTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_update_the_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $request = [
            'current_password' => 'password',
            'password' => 'password2',
            'password_confirmation' => 'password2',
        ];

        (new UpdateUserPassword)->update($user, $request);

        $user->refresh();
        $this->assertTrue(Hash::check($request['password'], $user->password));
    }

    /** @test */
    public function it_fails_if_current_password_is_wrong(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $request = [
            'current_password' => 'wrongpassword',
            'password' => 'password2',
            'password_confirmation' => 'password2',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateUserPassword)->update($user, $request);
    }

    /** @test */
    public function it_fails_if_confirmation_password_is_wrong(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $request = [
            'current_password' => 'password',
            'password' => 'password2',
            'password_confirmation' => 'wrongpassword',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateUserPassword)->update($user, $request);
    }
}
