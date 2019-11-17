<?php

namespace App\Http\Controllers\Company\Employee;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\User\Pronoun;
use Illuminate\Http\Request;
use App\Helpers\WorklogHelper;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\Pronoun as PronounResource;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\Resources\Company\Team\Team as TeamResource;
use App\Services\Company\Employee\Manager\UnassignManager;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;
use App\Http\Resources\Company\Position\Position as PositionResource;
use App\Http\Resources\Company\Employee\EmployeeList as EmployeeListResource;
use App\Http\Resources\Company\EmployeeStatus\EmployeeStatus as EmployeeStatusResource;

class EmployeeController extends Controller
{
    /**
     * Display the list of employees.
     *
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();

        $employees = $company->employees()->with('teams')
            ->with('status')
            ->orderBy('last_name', 'asc')
            ->get();

        return Inertia::render('Employee/Index', [
            'employees' => EmployeeListResource::collection($employees),
            'notifications' => Auth::user()->getLatestNotifications($company),
        ]);
    }

    /**
     * Display the detail of an employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $companyId, int $employeeId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = Employee::findOrFail($employeeId);

        $managers = $employee->getListOfManagers();
        $directReports = $employee->getListOfDirectReports();
        $positions = $company->positions()->get();
        $teams = $company->teams()->get();
        $worklogs = $employee->worklogs()->latest()->take(7)->get();
        $employeeStatuses = $company->employeeStatuses()->get();
        $pronouns = Pronoun::all();

        // building the collection containing the days of the week with the
        // worklogs
        $worklogs = collect([]);
        $currentDate = Carbon::now();
        for ($i = 0; $i < 5; $i++) {
            $day = $currentDate->copy()->startOfWeek()->addDays($i);
            $worklogs->push(WorklogHelper::getInformation($employee, $day));
        }

        // holidays

        return Inertia::render('Employee/Show', [
            'employee' => new EmployeeResource($employee),
            'notifications' => Auth::user()->getLatestNotifications($company),
            'managers' => EmployeeListResource::collection($managers),
            'directReports' => EmployeeListResource::collection($directReports),
            'positions' => PositionResource::collection($positions),
            'teams' => TeamResource::collection($teams),
            'worklogs' => $worklogs,
            'statuses' => EmployeeStatusResource::collection($employeeStatuses),
            'pronouns' => PronounResource::collection($pronouns),
        ]);
    }

    /**
     * Assign a manager to the employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
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

        return new EmployeeResource($manager);
    }

    /**
     * Assign a direct report to the employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
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

        return new EmployeeResource($directReport);
    }

    /**
     * Unassign a manager.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
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

        return new EmployeeResource($manager);
    }

    /**
     * Unassign a direct report.
     *
     * @param int $companyId
     * @param int $managerId
     * @return \Illuminate\Http\Response
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

        return new EmployeeResource($manager);
    }
}
