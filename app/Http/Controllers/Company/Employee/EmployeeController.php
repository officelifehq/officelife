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
use App\Services\Company\Employee\Manager\AssignManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Services\Company\Employee\Manager\UnassignManager;

class EmployeeController extends Controller
{
    /**
     * Display the list of employees.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function index(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();

        $employees = $company->employees()
            ->with('teams')
            ->with('position')
            ->notLocked()
            ->orderBy('last_name', 'asc')
            ->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'teams' => $employee->teams,
                'position' => (! $employee->position) ? null : [
                    'id' => $employee->position->id,
                    'title' => $employee->position->title,
                ],
            ]);
        }

        return Inertia::render('Employee/Index', [
            'employees' => $employeesCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the detail of an employee.
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

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->with('teams')
                ->with('company')
                ->with('pronoun')
                ->with('user')
                ->with('status')
                ->with('places')
                ->with('managers')
                ->with('workFromHomes')
                ->with('hardware')
                ->with('ships')
                ->with('skills')
                ->with('expenses')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // managers
        $managersOfEmployee = EmployeeShowViewHelper::managers($employee);

        // direct reports
        $directReportsOfEmployee = EmployeeShowViewHelper::directReports($employee);

        // worklogs
        $worklogsCollection = EmployeeShowViewHelper::worklogs($employee);

        // work from home
        $workFromHomeStats = EmployeeShowViewHelper::workFromHomeStats($employee);

        // questions
        $questions = EmployeeShowViewHelper::questions($employee);

        // hardware
        $hardware = EmployeeShowViewHelper::hardware($employee);

        // all the teams the employee belongs to
        $employeeTeams = EmployeeShowViewHelper::teams($employee->teams, $employee);

        // all teams in company
        $teams = EmployeeShowViewHelper::teams($company->teams()->get(), $employee);

        // all recent ships of this employee
        $ships = EmployeeShowViewHelper::recentShips($employee);

        // all skills of this employee
        $skills = EmployeeShowViewHelper::skills($employee);

        return Inertia::render('Employee/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'employee' => $employee->toObject(),
            'managersOfEmployee' => $managersOfEmployee,
            'directReports' => $directReportsOfEmployee,
            'worklogs' => $worklogsCollection,
            'workFromHomes' => $workFromHomeStats,
            'questions' => $questions,
            'hardware' => $hardware,
            'employeeTeams' => $employeeTeams,
            'positions' => PositionCollection::prepare($company->positions()->get()),
            'teams' => $teams,
            'statuses' => EmployeeStatusCollection::prepare($company->employeeStatuses()->get()),
            'pronouns' => PronounCollection::prepare(Pronoun::all()),
            'ships' => $ships,
            'skills' => $skills,
        ]);
    }

    /**
     * Assign a manager to the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function assignManager(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'manager_id' => $request->input('id'),
        ];

        $manager = (new AssignManager)->execute($request);

        return response()->json([
            'data' => [
                'id' => $manager->id,
                'name' => $manager->name,
                'avatar' => $manager->avatar,
                'position' => (! $manager->position) ? null : [
                    'id' => $manager->position->id,
                    'title' => $manager->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $manager->company,
                    'employee' => $manager,
                ]),
            ],
        ], 200);
    }

    /**
     * Assign a direct report to the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function assignDirectReport(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $request->input('id'),
            'manager_id' => $employeeId,
        ];

        (new AssignManager)->execute($data);

        $directReport = Employee::findOrFail($request->input('id'));

        return response()->json([
            'data' =>[
                'id' => $directReport->id,
                'name' => $directReport->name,
                'avatar' => $directReport->avatar,
                'position' => (! $directReport->position) ? null : [
                    'id' => $directReport->position->id,
                    'title' => $directReport->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $directReport->company,
                    'employee' => $directReport,
                ]),
            ],
        ], 200);
    }

    /**
     * Unassign a manager.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function unassignManager(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'manager_id' => $request->input('id'),
        ];

        $manager = (new UnassignManager)->execute($request);

        return response()->json([
            'data' => [
                'id' => $manager->id,
            ],
        ], 200);
    }

    /**
     * Unassign a direct report.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $managerId
     *
     * @return Response
     */
    public function unassignDirectReport(Request $request, int $companyId, int $managerId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $request->input('id'),
            'manager_id' => $managerId,
        ];

        $manager = (new UnassignManager)->execute($request);

        return response()->json([
            'data' => [
                'id' => $manager->id,
            ],
        ], 200);
    }
}
