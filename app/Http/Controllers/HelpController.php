<?php

namespace App\Http\Controllers;

use App\Services\User\Preferences\ChangeHelpPreferences;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    /**
     * Toggle help preferences.
     */
    public function toggle(Request $request): JsonResponse
    {
        $data = [
            'user_id' => Auth::user()->id,
            'visibility' => ! Auth::user()->show_help,
        ];

        (new ChangeHelpPreferences)->execute($data);

        return response()->json([
            'data' => ! Auth::user()->show_help,
        ], 200);
    }
}
