<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
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

        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'locale-changed');
    }
}
