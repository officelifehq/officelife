<?php

namespace App\Http\Controllers\Company\Employee\Administration;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\PermissionHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;

class EmployeeAdministrationController extends Controller
{
    /**
     * Display the detail of an employee’s administration panel.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return mixed
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
                ->with('hardware')
                ->with('softwares')
                ->with('expenses')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // information about what the logged employee can do
        $permissions = PermissionHelper::permissions($loggedEmployee, $employee);

        // hardware
        $hardware = EmployeeShowViewHelper::hardware($employee, $permissions);

        // softwares
        $softwares = EmployeeShowViewHelper::softwares($employee, $permissions);

        // all expenses of this employee
        $expenses = EmployeeShowViewHelper::expenses($employee, $permissions, $loggedEmployee);

        // information about the timesheets
        $timesheets = EmployeeShowViewHelper::timesheets($employee, $permissions);

        // information about the employee that the logged employee consults, that depends on what the logged Employee has the right to see
        $employee = EmployeeShowViewHelper::informationAboutEmployee($employee, $permissions, $loggedEmployee);

        return Inertia::render('Employee/Administration/Index', [
            'menu' => 'administration',
            'employee' => $employee,
            'permissions' => $permissions,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'hardware' => $hardware,
            'expenses' => $expenses,
            'softwares' => $softwares,
            'timesheets' => $timesheets,
        ]);
    }
}
