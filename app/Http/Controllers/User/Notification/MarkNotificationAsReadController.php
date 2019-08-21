<?php

namespace App\Http\Controllers\User\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\User\Notification\MarkNotificationsAsRead;

class MarkNotificationAsReadController extends Controller
{
    /**
     * Mark the notifications as read.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = (new MarkNotificationsAsRead)->execute([
            'user_id' => Auth::user()->id,
        ]);

        return response()->json([
            'result' => $result,
        ]);
    }
}
