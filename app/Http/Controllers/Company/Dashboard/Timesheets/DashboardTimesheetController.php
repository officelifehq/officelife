<?php

namespace App\Http\Controllers\Company\Dashboard\Timesheets;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use App\Models\Company\Timesheet;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateDashboardPreference;
use App\Http\ViewHelpers\Dashboard\DashboardViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Timesheet\SubmitTimesheet;
use App\Http\ViewHelpers\Dashboard\DashboardTimesheetViewHelper;
use App\Services\Company\Employee\Timesheet\DestroyTimesheetRow;
use App\Services\Company\Employee\Timesheet\CreateOrGetTimesheet;
use App\Services\Company\Employee\Timesheet\CreateTimeTrackingEntry;

class DashboardTimesheetController extends Controller
{
    /**
     * See the detail of the current timesheet.
     *
     * @return Response
     */
    public function index(): Response
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        UpdateDashboardPreference::dispatch([
            'employee_id' => $loggedEmployee->id,
            'company_id' => $loggedCompany->id,
            'view' => 'timesheet',
        ])->onQueue('low');

        $currentTimesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $loggedEmployee->id,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $timesheetInformation = DashboardTimesheetViewHelper::show($currentTimesheet);
        $daysInHeader = DashboardTimesheetViewHelper::daysHeader($currentTimesheet);
        $nextTimesheet = DashboardTimesheetViewHelper::nextTimesheet($currentTimesheet, $loggedEmployee);
        $previousTimesheet = DashboardTimesheetViewHelper::previousTimesheet($currentTimesheet, $loggedEmployee);
        $approverInformation = DashboardTimesheetViewHelper::approverInformation($currentTimesheet, $loggedEmployee);
        $currentTimesheet = DashboardTimesheetViewHelper::currentTimesheet($loggedEmployee);
        $rejectedTimesheets = DashboardTimesheetViewHelper::rejectedTimesheets($loggedEmployee);

        return Inertia::render('Dashboard/Timesheet/Index', [
            'employee' => DashboardViewHelper::information($loggedEmployee, 'timesheet'),
            'timesheet' => $timesheetInformation,
            'approverInformation' => $approverInformation,
            'daysHeader' => $daysInHeader,
            'nextTimesheet' => $nextTimesheet,
            'previousTimesheet' => $previousTimesheet,
            'currentTimesheet' => $currentTimesheet,
            'rejectedTimesheets' => $rejectedTimesheets,
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Display the page of the given timesheet.
     */
    public function show(Request $request, int $companyId, int $timesheetId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $timesheet = Timesheet::where('company_id', $loggedCompany->id)
                ->where('employee_id', $loggedEmployee->id)
                ->findOrFail($timesheetId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $timesheetInfo = DashboardTimesheetViewHelper::show($timesheet);
        $daysInHeader = DashboardTimesheetViewHelper::daysHeader($timesheet);
        $nextTimesheet = DashboardTimesheetViewHelper::nextTimesheet($timesheet, $loggedEmployee);
        $previousTimesheet = DashboardTimesheetViewHelper::previousTimesheet($timesheet, $loggedEmployee);
        $currentTimesheet = DashboardTimesheetViewHelper::currentTimesheet($loggedEmployee);
        $approverInformation = DashboardTimesheetViewHelper::approverInformation($timesheet, $loggedEmployee);
        $rejectedTimesheets = DashboardTimesheetViewHelper::rejectedTimesheets($loggedEmployee);

        return Inertia::render('Dashboard/Timesheet/Index', [
            'employee' => DashboardViewHelper::information($loggedEmployee, 'timesheet'),
            'daysHeader' => $daysInHeader,
            'timesheet' => $timesheetInfo,
            'approverInformation' => $approverInformation,
            'nextTimesheet' => $nextTimesheet,
            'previousTimesheet' => $previousTimesheet,
            'currentTimesheet' => $currentTimesheet,
            'rejectedTimesheets' => $rejectedTimesheets,
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Get the projects the logged employee belongs to.
     *
     * @return JsonResponse
     */
    public function projects(): JsonResponse
    {
        $employee = InstanceHelper::getLoggedEmployee();

        $projects = DashboardTimesheetViewHelper::projects($employee);

        return response()->json([
            'data' => $projects,
        ], 200);
    }

    /**
     * Get the tasks for the given project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $timesheetId
     * @param int $projectId
     * @return JsonResponse
     */
    public function tasks(Request $request, int $companyId, int $timesheetId, int $projectId): ?JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $project = Project::where('company_id', $company->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $timesheet = Timesheet::where('employee_id', $employee->id)
                ->findOrFail($timesheetId);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        $tasks = DashboardTimesheetViewHelper::availableTasks($project, $timesheet);

        return response()->json([
            'data' => $tasks,
        ], 200);
    }

    public function createTimeTrackingEntry(Request $request, int $companyId, int $timesheetId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $timesheet = Timesheet::where('employee_id', $employee->id)
                ->findOrFail($timesheetId);
        } catch (ModelNotFoundException $e) {
            return;
        }

        // we need to convert the day that is passed to a date
        // `0` is monday
        $date = $timesheet->started_at->addDays($request->input('day'))->format('Y-m-d');

        $entry = (new CreateTimeTrackingEntry)->execute([
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'project_id' => $request->input('project_id'),
            'project_task_id' => $request->input('project_task_id'),
            'duration' => $request->input('durationInMinutes'),
            'date' => $date,
        ]);

        return response()->json([
            'data' => $entry,
        ], 201);
    }

    /**
     * Submit the timesheet for validation.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $timesheetId
     */
    public function submit(Request $request, int $companyId, int $timesheetId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $timesheet = Timesheet::where('employee_id', $employee->id)
                ->findOrFail($timesheetId);
        } catch (ModelNotFoundException $e) {
            return;
        }

        // we need to convert the day that is passed to a date
        // `0` is monday
        $date = $timesheet->started_at->addDays($request->input('day'))->format('Y-m-d');

        (new SubmitTimesheet)->execute([
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'timesheet_id' => $timesheetId,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Destroy the given timesheet row.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $timesheetId
     */
    public function destroyRow(Request $request, int $companyId, int $timesheetId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $timesheet = Timesheet::where('employee_id', $employee->id)
                ->findOrFail($timesheetId);
        } catch (ModelNotFoundException $e) {
            return;
        }

        (new DestroyTimesheetRow)->execute([
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'timesheet_id' => $timesheetId,
            'project_id' => $request->input('project_id'),
            'project_task_id' => $request->input('project_task_id'),
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
