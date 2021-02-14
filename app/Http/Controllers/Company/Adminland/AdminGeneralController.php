<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\Company\RenameCompany;
use App\Http\ViewHelpers\Adminland\AdminGeneralViewHelper;
use App\Services\Company\Adminland\Company\UpdateCompanyCurrency;

class AdminGeneralController extends Controller
{
    /**
     * Show the General settings company page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $information = AdminGeneralViewHelper::information($company);
        $currencies = AdminGeneralViewHelper::currencies();

        return Inertia::render('Adminland/General/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'information' => $information,
            'currencies' => $currencies,
        ]);
    }

    /**
     * Rename the company.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function rename(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
        ];

        (new RenameCompany)->execute($data);

        return response()->json([
            'data' => true,
        ], 201);
    }

    /**
     * Update the company’s currency.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function currency(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'currency' => $request->input('currency'),
        ];

        (new UpdateCompanyCurrency)->execute($data);

        return response()->json([
            'data' => true,
        ], 201);
    }
}
