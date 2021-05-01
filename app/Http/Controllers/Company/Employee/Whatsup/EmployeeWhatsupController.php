<?php

namespace App\Http\Controllers\Company\Employee\Whatsup;

use Carbon\Carbon;
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
     * @param int $year
     * @return Response
     */
    public function index(Request $request, int $companyId, int $employeeId, int $year = null): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $employee = Employee::findOrFail($employeeId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $startDate = Carbon::now()->startOfYear();
        if ($year) {
            $startDate = $startDate->setYear($year);
        }

        $endDate = $startDate->copy()->endOfYear();

        $information = EmployeeWhatsUpViewHelper::information($employee);
        $oneOnOnes = EmployeeWhatsUpViewHelper::oneOnOnes($employee, $startDate, $endDate, $loggedCompany);
        $projects = EmployeeWhatsUpViewHelper::projects($employee, $startDate, $endDate, $loggedCompany);
        $timesheets = EmployeeWhatsUpViewHelper::timesheets($employee, $startDate, $endDate);
        $external = EmployeeWhatsUpViewHelper::external($employee, $startDate, $endDate);
        $workFromHome = EmployeeWhatsUpViewHelper::workFromHome($employee, $startDate, $endDate);
        $worklogs = EmployeeWhatsUpViewHelper::worklogs($employee, $startDate, $endDate);
        $years = EmployeeWhatsUpViewHelper::yearsInCompany($employee, $loggedCompany, $startDate->year);

        return Inertia::render('Employee/Whatsup/Index', [
            'employee' => $information,
            'oneOnOnes' => $oneOnOnes,
            'projects' => $projects,
            'timesheets' => $timesheets,
            'external' => $external,
            'workFromHome' => $workFromHome,
            'worklogs' => $worklogs,
            'years' => $years,
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }
}
