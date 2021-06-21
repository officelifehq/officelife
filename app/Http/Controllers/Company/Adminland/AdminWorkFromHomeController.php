<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminWorkFromHomeViewHelper;
use App\Services\Company\Adminland\WorkFromHome\ToggleWorkFromHomeProcess;

class AdminWorkFromHomeController extends Controller
{
    /**
     * Show the work from home page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $details = AdminWorkFromHomeViewHelper::index($company);

        return Inertia::render('Adminland/WorkFromHome/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'process' => $details,
        ]);
    }

    /**
     * Toggle the work from home setting in the company.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
        ];

        $company = (new ToggleWorkFromHomeProcess)->execute($data);

        return response()->json([
            'data' => [
                'enabled' => $company->work_from_home_enabled,
            ],
        ], 200);
    }
}
