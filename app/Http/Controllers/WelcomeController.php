<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page.
     *
     * @return Response
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
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Hide the welcome page.
     *
     * @return Response
     */
    public function hide()
    {
        $employee = InstanceHelper::getLoggedEmployee();

        $employee->display_welcome_message = false;
        $employee->save();

        return response()->json([
            'data' => [
                'company_id' => $employee->company_id,
            ],
        ], 200);
    }
}
