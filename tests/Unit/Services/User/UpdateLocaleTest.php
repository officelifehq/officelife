<?php

namespace Tests\Unit\Services\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Services\User\UpdateLocale;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateLocaleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_update_the_locale(): void
    {
        $user = User::factory()->create([
            'locale' => 'xx',
        ]);

        $request = [
            'user_id' => $user->id,
            'locale' => 'en',
        ];

        (new UpdateLocale)->execute($request);

        $user->refresh();
        $this->assertEquals('en', $user->locale);
    }

    /** @test */
    public function it_fails_if_given_locale_is_wrong(): void
    {
        $user = User::factory()->create([
            'locale' => 'en',
        ]);

        $request = [
            'user_id' => $user->id,
            'locale' => 'xx',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateLocale)->execute($request);
    }

    /** @test */
    public function it_fails_if_given_locale_is_empty(): void
    {
        $user = User::factory()->create([
            'locale' => 'en',
        ]);

        $request = [
            'user_id' => $user->id,
            'locale' => '',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateLocale)->execute($request);
    }
}
