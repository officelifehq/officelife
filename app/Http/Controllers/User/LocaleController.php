<?php

namespace App\Http\Controllers\User\Notification;

use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Project\UpdateLocale;

class LocaleController extends Controller
{
    /**
     * Update the user locale.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return mixed
     */
    public function update(Request $request)
    {
        (new UpdateLocale)->execute([
            'user_id' => Auth::user()->id,
            'locale' => '',
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
