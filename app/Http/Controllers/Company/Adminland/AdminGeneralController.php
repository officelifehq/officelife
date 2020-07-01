<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
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
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $information = AdminGeneralViewHelper::information($company);
        $currencies = AdminGeneralViewHelper::currencies($company);

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
     *
     * @return Response
     */
    public function rename(Request $request, $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
        ];

        (new RenameCompany)->execute($request);

        return response()->json([
            'data' => true,
        ], 201);
    }

    /**
     * Update the company’s currency.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function currency(Request $request, $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'currency' => $request->input('currency'),
        ];

        (new UpdateCompanyCurrency)->execute($request);

        return response()->json([
            'data' => true,
        ], 201);
    }
}
