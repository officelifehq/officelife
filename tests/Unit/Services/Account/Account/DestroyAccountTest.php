<?php

namespace Tests\Unit\Services\Account\Account;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Validation\ValidationException;
use App\Services\Account\Account\DestroyAccount;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DestroyAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_the_account()
    {
        $author = factory(User::class)->create([]);

        $request = [
            'account_id' => $author->account_id,
            'author_id' => $author->id,
        ];

        (new DestroyAccount)->execute($request);

        $this->assertDatabaseMissing('accounts', [
            'id' => $author->account_id,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $author = factory(User::class)->create([]);

        $request = [
            'author_id' => $author->id,
        ];

        $this->expectException(ValidationException::class);
        (new DestroyAccount)->execute($request);
    }
}
