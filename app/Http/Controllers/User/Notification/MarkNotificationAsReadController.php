<?php

namespace App\Http\Controllers\User\Notification;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Notification\MarkNotificationsAsRead;

class MarkNotificationAsReadController extends Controller
{
    /**
     * Mark the notifications as read.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $result = (new MarkNotificationsAsRead)->execute([
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $loggedEmployee->id,
        ]);

        return response()->json([
            'result' => $result,
        ]);
    }
}
