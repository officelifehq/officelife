<?php

namespace App\Http\Controllers\Company\Employee\Performance;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\PermissionHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Http\ViewHelpers\Employee\EmployeePerformanceViewHelper;

class EmployeePerformanceController extends Controller
{
    /**
     * Display the performance of an employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return Response
     */
    public function show(Request $request, int $companyId, int $employeeId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $employee = Employee::where('company_id', $company->id)
                ->where('id', $employeeId)
                ->with('company')
                ->with('user')
                ->with('status')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // information about the logged employee
        $permissions = PermissionHelper::permissions($loggedEmployee, $employee);

        // the latest one on ones
        $oneOnOnes = EmployeeShowViewHelper::oneOnOnes($employee, $permissions, $loggedEmployee);

        // surveys
        $surveys = EmployeePerformanceViewHelper::latestRateYourManagerSurveys($employee);

        // information about the employee, that depends on what the logged Employee can see
        $employee = EmployeeShowViewHelper::informationAboutEmployee($employee, $permissions, $loggedEmployee);

        return Inertia::render('Employee/Performance/Index', [
            'menu' => 'performance',
            'employee' => $employee,
            'permissions' => $permissions,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'surveys' => $surveys,
            'oneOnOnes' => $oneOnOnes,
        ]);
    }
}
