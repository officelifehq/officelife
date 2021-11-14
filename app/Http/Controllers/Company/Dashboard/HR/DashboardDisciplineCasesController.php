<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRTimesheetViewHelper;

class DashboardDisciplineCasesController extends Controller
{
    /**
     * Show the list of discipline cases, opened or closed.
     *
     * @return mixed
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $employees = DashboardHRTimesheetViewHelper::timesheetApprovalsForEmployeesWithoutManagers($company);

        return Inertia::render('Dashboard/HR/DisciplineCases/Index', [
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
