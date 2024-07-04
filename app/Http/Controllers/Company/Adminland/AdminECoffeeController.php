<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Adminland\AdminECoffeeViewHelper;
use App\Services\Company\Adminland\ECoffee\ToggleECoffeeProcess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminECoffeeController extends Controller
{
    /**
     * Show the eCoffee page.
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
