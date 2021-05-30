<?php

namespace App\Http\Controllers\User;

use Inertia\Response;
use Illuminate\Http\Request;
use App\Services\User\UpdateLocale;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    /**
     * Update the user locale.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        (new UpdateLocale)->execute([
            'user_id' => Auth::user()->id,
            'locale' => $request->input('locale'),
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
