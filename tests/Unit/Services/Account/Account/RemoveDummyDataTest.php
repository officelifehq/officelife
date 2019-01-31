<?php

namespace Tests\Unit\Services\Account\Account;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\Account\Account\GenerateDummyData;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RemoveDummyDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_five_users_without_team()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'account_id' => $user->account_id,
            'author_id' => $user->id,
        ];

        (new GenerateDummyData)->execute($request);

        $count = DB::table('users')
            ->where('account_id', $user->account_id)
            ->where('is_dummy', true)
            ->count();

        $this->assertEquals(
            32,
            $count
        );

        $this->assertDatabaseHas('accounts', [
            'has_dummy_data' => true,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'account_id' => $user->account_id,
            'author_id' => 3,
        ];

        $this->expectException(ValidationException::class);
        (new GenerateDummyData)->execute($request);
    }
}
