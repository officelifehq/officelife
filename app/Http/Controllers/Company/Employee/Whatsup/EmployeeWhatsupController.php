<?php

namespace App\Http\Controllers\Company\Employee\Whatsup;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeWhatsUpViewHelper;

class EmployeeWhatsupController extends Controller
{
    /**
     * Show the What's up page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return Response
     */
    public function index(Request $request, int $companyId, int $employeeId): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $employee = Employee::findOrFail($employeeId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $information = EmployeeWhatsUpViewHelper::information($employee);

        return Inertia::render('Employee/Whatsup/Index', [
            'employee' => $information,
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }
}
