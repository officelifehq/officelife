<?php

namespace Tests\Unit\Models\Account;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Account;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_many_users()
    {
        $account = factory(Account::class)->create();
        factory(User::class, 3)->create([
            'account_id' => $account->id,
        ]);

        $this->assertTrue($account->users()->exists());
    }
}
