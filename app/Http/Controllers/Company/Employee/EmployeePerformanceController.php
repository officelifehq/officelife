<?php

namespace App\Http\Controllers\Company\Employee;

use Inertia\Inertia;
use App\Models\User\Pronoun;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\PronounCollection;
use App\Http\Collections\PositionCollection;
use App\Http\Collections\EmployeeStatusCollection;
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
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->with('teams')
                ->with('company')
                ->with('pronoun')
                ->with('user')
                ->with('status')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // surveys
        $surveys = EmployeePerformanceViewHelper::latestRateYourManagerSurveys($employee);

        // if there are no surveys, redirect to the employee profile page
        if (! $surveys) {
            return redirect()->route('employees.show', [
                'company' => $company,
                'employee' => $employee,
            ]);
        }

        // all the teams the employee belongs to
        $employeeTeams = EmployeeShowViewHelper::teams($employee->teams, $employee);

        // all teams in company
        $teams = $company->teams()->with('leader')->get();
        $teams = EmployeeShowViewHelper::teams($teams, $employee);

        // information about the logged employee
        $loggedEmployee = EmployeeShowViewHelper::informationAboutLoggedEmployee($loggedEmployee, $employee);

        // information about the employee, that depends on what the logged Employee can see
        $employee = EmployeeShowViewHelper::informationAboutEmployee($employee, $loggedEmployee);

        return Inertia::render('Employee/Performance/Index', [
            'menu' => 'performance',
            'employee' => $employee,
            'loggedEmployee' => $loggedEmployee,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'employeeTeams' => $employeeTeams,
            'positions' => PositionCollection::prepare($company->positions()->get()),
            'teams' => $teams,
            'statuses' => EmployeeStatusCollection::prepare($company->employeeStatuses()->get()),
            'pronouns' => PronounCollection::prepare(Pronoun::all()),
            'surveys' => $surveys,
        ]);
    }
}
