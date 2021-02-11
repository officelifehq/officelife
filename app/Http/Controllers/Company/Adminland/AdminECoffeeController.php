<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminECoffeeViewHelper;
use App\Services\Company\Adminland\ECoffee\ToggleECoffeeProcess;

class AdminECoffeeController extends Controller
{
    /**
     * Show the eCoffee page.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $eCoffeeDetails = AdminECoffeeViewHelper::eCoffee($company);

        return Inertia::render('Adminland/ECoffee/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'ecoffee' => $eCoffeeDetails,
        ]);
    }

    /**
     * Toggle the eCoffee session in the company.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
        ];

        $company = (new ToggleECoffeeProcess)->execute($data);

        return response()->json([
            'data' => [
                'enabled' => $company->e_coffee_enabled,
            ],
        ], 200);
    }
}
