<?php

namespace App\Http\Controllers\Company\Employee\Performance\OneOnOnes;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeOneOnOneViewHelper;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeOneOnOneController extends Controller
{
    /**
     * Display the list of one on ones of this employee.
     *
     * @return mixed
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $oneOnOnes = $employee->oneOnOneEntriesAsEmployee()
            ->with('manager')
            ->with('talkingPoints')
            ->with('actionItems')
            ->with('notes')
            ->orderBy('happened_at', 'desc')
            ->get();

        return Inertia::render('Employee/Performance/OneOnOnes/Index', [
            'employee' => [
                'id' => $employeeId,
                'name' => $employee->name,
            ],
            'oneOnOnes' => EmployeeOneOnOneViewHelper::list($oneOnOnes, $employee, $loggedEmployee),
            'statistics' => EmployeeOneOnOneViewHelper::stats($oneOnOnes),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Display a single one on one.
     *
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $employeeId, int $oneOnOneId)
    {
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $entry = OneOnOneEntry::with('employee')
                ->with('manager')
                ->with('actionItems')
                ->with('talkingPoints')
                ->with('notes')
                ->findOrFail($oneOnOneId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        if ($entry->manager_id != $employee->id && $entry->employee_id != $employee->id) {
            return redirect('home');
        }

        $details = EmployeeOneOnOneViewHelper::details($entry, $employee);

        return Inertia::render('Employee/Performance/OneOnOnes/Show', [
            'employee' => [
                'id' => $employeeId,
                'name' => $employee->name,
            ],
            'entry' => $details,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
