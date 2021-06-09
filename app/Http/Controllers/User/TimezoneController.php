<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Helpers\TimezoneHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\User\UpdateTimezone;

class TimezoneController extends Controller
{
    /**
     * Get all timezones.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse(TimezoneHelper::getListOfTimezones(), 200);
    }

    /**
     * Update the user timezone.
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        (new UpdateTimezone)->execute([
            'user_id' => Auth::user()->id,
            'timezone' => $request->input('timezone'),
        ]);

        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'timezone-changed');
    }
}
