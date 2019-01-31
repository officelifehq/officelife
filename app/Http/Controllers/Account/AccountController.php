<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\Account\Account\RemoveDummyData;
use App\Services\Account\Account\GenerateDummyData;

class AccountController extends Controller
{
    /**
     * Generate or remove fake data for the account.
     *
     * @return \Illuminate\Http\Response
     */
    public function dummy()
    {
        if (auth()->user()->account->has_dummy_data) {
            (new RemoveDummyData)->execute([
                'account_id' => auth()->user()->account_id,
                'author_id' => auth()->user()->id,
            ]);

            return redirect('dashboard');
        }

        (new GenerateDummyData)->execute([
            'account_id' => auth()->user()->account_id,
            'author_id' => auth()->user()->id,
        ]);

        return redirect('dashboard');
    }
}
