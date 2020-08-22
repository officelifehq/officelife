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
use App\Http\ViewHelpers\Dashboard\DashboardMeViewHelper;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;

class EmployeePerformanceController extends Controller
{
    /**
     * Display the performance of an employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
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

        // all the teams the employee belongs to
        $employeeTeams = EmployeeShowViewHelper::teams($employee->teams, $employee);

        // all teams in company
        $teams = $company->teams()->with('leader')->get();
        $teams = EmployeeShowViewHelper::teams($teams, $employee);

        return Inertia::render('Employee/ShowPerformance', [
            'menu' => 'performance',
            'employee' => $employee->toObject(),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'employeeTeams' => $employeeTeams,
            'positions' => PositionCollection::prepare($company->positions()->get()),
            'teams' => $teams,
            'statuses' => EmployeeStatusCollection::prepare($company->employeeStatuses()->get()),
            'pronouns' => PronounCollection::prepare(Pronoun::all()),
            'surveys' => DashboardMeViewHelper::latestRateYourManagerSurveys($employee),
            'isAccountant' => $loggedEmployee->can_manage_expenses,
        ]);
    }
}
