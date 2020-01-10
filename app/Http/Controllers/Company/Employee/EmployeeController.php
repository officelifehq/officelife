<?php

namespace App\Http\Controllers\Company\Employee;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\User\Pronoun;
use Illuminate\Http\Request;
use App\Helpers\WorklogHelper;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Manager\AssignManager;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Manager\UnassignManager;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

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
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $companyId, int $employeeId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->with('teams')
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $companyPositions = $company->positions()->get();
        $companyTeams = $company->teams()->get();
        $companyEmployeeStatuses = $company->employeeStatuses()->get();
        $companyPronouns = Pronoun::all();

        // managers
        $managers = $employee->managers;
        $managersOfEmployee = collect([]);
        foreach ($managers as $manager) {
            $manager = $manager->manager;

            $managersOfEmployee->push([
                'id' => $manager->id,
                'name' => $manager->name,
                'avatar' => $manager->avatar,
                'position' => ($manager->position) ? $manager->position->title : null,
            ]);
        }

        // direct reports
        $directReports = $employee->directReports;
        $directReportsOfEmployee = collect([]);
        foreach ($directReports as $directReport) {
            $directReport = $directReport->directReport;

            $directReportsOfEmployee->push([
                'id' => $directReport->id,
                'name' => $directReport->name,
                'avatar' => $directReport->avatar,
                'position' => ($directReport->position) ? $directReport->position->title : null,
            ]);
        }

        // building the collection containing the days of the week with the
        // worklogs
        $worklogs = $employee->worklogs()->latest()->take(7)->get();
        $morales = $employee->morales()->latest()->take(7)->get();
        $worklogsCollection = collect([]);
        $currentDate = Carbon::now();
        for ($i = 0; $i < 5; $i++) {
            $day = $currentDate->copy()->startOfWeek()->addDays($i);

            $worklog = $worklogs->first(function ($worklog) use ($day) {
                return $worklog->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $morale = $morales->first(function ($morale) use ($day) {
                return $morale->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $worklogsCollection->push(
                WorklogHelper::getDailyInformationForEmployee($worklog, $morale, $day)
            );
        }

        // information about the employee
        $employeeObject = [
            'id' => $employee->id,
            'name' => $employee->name,
            'avatar' => $employee->avatar,
            'permission_level' => $employee->getPermissionLevel(),
            'pronoun' => (!$employee->pronoun) ? null : [
                'id' => $employee->pronoun->id,
                'label' => $employee->pronoun->label,
            ],
            'user' => (!$employee->user) ? null : [
                'id' => $employee->user->id,
            ],
        ];

        return Inertia::render('Employee/Show', [
            'employee' => $employeeObject,
            'employeeTeams' => $employee->teams,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'managersOfEmployee' => $managersOfEmployee,
            'directReports' => $directReportsOfEmployee,
            'positions' => $companyPositions,
            'teams' => $companyTeams,
            'worklogs' => $worklogsCollection,
            'statuses' => $companyEmployeeStatuses,
            'pronouns' => $companyPronouns,
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
