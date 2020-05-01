<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\User\Preferences\ChangeHelpPreferences;

class HelpController extends Controller
{
    /**
     * Perform search of an employee from the header.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function toggle(Request $request)
    {
        $request = [
            'user_id' => Auth::user()->id,
            'visibility' => ! Auth::user()->show_help,
        ];

        (new ChangeHelpPreferences)->execute($request);

        return response()->json([
            'data' => ! Auth::user()->show_help,
        ], 200);
    }
}
