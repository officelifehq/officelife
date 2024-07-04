<?php

namespace Tests\Unit\Services\User;

use App\Models\User\User;
use App\Services\User\ResetUserPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ResetUserPasswordTest extends TestCase
{
    /** @test */
    public function it_reset_the_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $request = [
            'password' => 'password2',
            'password_confirmation' => 'password2',
        ];

        (new ResetUserPassword)->reset($user, $request);

        $user->refresh();
        $this->assertTrue(Hash::check($request['password'], $user->password));
    }

    /** @test */
    public function it_fails_if_confirmation_password_is_wrong(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $request = [
            'password' => 'password2',
            'password_confirmation' => 'wrongpassword',
        ];

        $this->expectException(ValidationException::class);
        (new ResetUserPassword)->reset($user, $request);
    }
}
