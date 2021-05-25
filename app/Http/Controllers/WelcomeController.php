<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Inertia\Response
     */
    public function index()
    {
        $employee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        if (! $employee->display_welcome_message) {
            return redirect($company->id.'/dashboard');
        }

        return Inertia::render('Welcome/Index', [
            'employees' => [
                'id' => $employee->id,
            ],
            'url' => 'https://docs.officelife.io/documentation/setup-guide.html',
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Hide the welcome page.
     *
     * @return JsonResponse
     */
    public function hide(): JsonResponse
    {
        $employee = InstanceHelper::getLoggedEmployee();

        Employee::where('id', $employee->id)->update([
            'display_welcome_message' => false,
        ]);

        return response()->json([
            'data' => [
                'company_id' => $employee->company_id,
            ],
        ], 200);
    }
}
