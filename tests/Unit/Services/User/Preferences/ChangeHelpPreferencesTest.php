<?php

namespace Tests\Unit\Services\User\Preferences;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\User\Preferences\ChangeHelpPreferences;

class ChangeHelpPreferencesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_the_help_preference(): void
    {
        $user = User::factory()->create([]);

        $request = [
            'user_id' => $user->id,
            'visibility' => false,
        ];

        $bool = (new ChangeHelpPreferences)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'show_help' => false,
        ]);

        $this->assertTrue($bool);

        $request = [
            'user_id' => $user->id,
            'visibility' => true,
        ];

        $bool = (new ChangeHelpPreferences)->execute($request);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'show_help' => true,
        ]);

        $this->assertTrue($bool);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'action' => 'account_created',
        ];

        $this->expectException(ValidationException::class);
        (new ChangeHelpPreferences)->execute($request);
    }
}
