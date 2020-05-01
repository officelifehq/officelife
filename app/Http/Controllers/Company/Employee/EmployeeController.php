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
use App\Http\Collections\TeamCollection;
use App\Http\Collections\PronounCollection;
use App\Http\Collections\PositionCollection;
use App\Http\Collections\EmployeeStatusCollection;
use App\Services\Company\Employee\Manager\AssignManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Manager\UnassignManager;
use App\Http\ViewHelpers\Company\Employee\EmployeeShowViewHelper;

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
                ->with('pronoun')
                ->with('user')
                ->with('status')
                ->with('places')
                ->with('managers')
                ->with('workFromHomes')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // managers
        $managersOfEmployee = EmployeeShowViewHelper::managers($employee);

        // direct reports
        $directReportsOfEmployee = collect([]);
        $directReportsOfEmployee = EmployeeShowViewHelper::directReports($employee);

        // worklogs
        $worklogsCollection = EmployeeShowViewHelper::worklogs($employee);

        // work from home
        $workFromHomeStats = EmployeeShowViewHelper::workFromHomeStats($employee);

        // questions
        $questions = EmployeeShowViewHelper::questions($employee);

        return Inertia::render('Employee/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'employee' => $employee->toObject(),
            'managersOfEmployee' => $managersOfEmployee,
            'directReports' => $directReportsOfEmployee,
            'worklogs' => $worklogsCollection,
            'workFromHomes' => $workFromHomeStats,
            'questions' => $questions,
            'employeeTeams' => TeamCollection::prepare($employee->teams),
            'positions' => PositionCollection::prepare($company->positions()->get()),
            'teams' => TeamCollection::prepare($company->teams()->get()),
            'statuses' => EmployeeStatusCollection::prepare($company->employeeStatuses()->get()),
            'pronouns' => PronounCollection::prepare(Pronoun::all()),
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
            'manager_id' => $request->get('id'),
        ];

        $manager = (new AssignManager)->execute($request);

        return response()->json([
            'data' => $manager->toObject(),
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
            'employee_id' => $request->get('id'),
            'manager_id' => $employeeId,
        ];

        (new AssignManager)->execute($data);

        $directReport = Employee::findOrFail($request->get('id'));

        return response()->json([
            'data' => $directReport->toObject(),
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
            'manager_id' => $request->get('id'),
        ];

        $manager = (new UnassignManager)->execute($request);

        return response()->json([
            'data' => $manager->toObject(),
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
            'employee_id' => $request->get('id'),
            'manager_id' => $managerId,
        ];

        $manager = (new UnassignManager)->execute($request);

        return response()->json([
            'data' => $manager->toObject(),
        ], 200);
    }
}
