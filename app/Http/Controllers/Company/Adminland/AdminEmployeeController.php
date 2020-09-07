<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Employee\LockEmployee;
use App\Services\Company\Adminland\Employee\UnlockEmployee;
use App\Services\Company\Adminland\Employee\DestroyEmployee;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;

class AdminEmployeeController extends Controller
{
    /**
     * Show the list of employees.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employees = $company->employees()
            ->orderBy('created_at', 'desc')
            ->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'permission_level' => $employee->permission_level,
                'avatar' => $employee->avatar,
                'invitation_link' => $employee->invitation_link,
                'invited' => ! $employee->invitation_used_at && $employee->invitation_link ? true : false,
                'lock_status' => $employee->locked,
                'url_view' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'url_delete' => route('account.delete', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'url_lock' => route('account.lock', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
                'url_unlock' => route('account.unlock', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        $numberOfLockedAccounts = $employees->filter(function ($employee) {
            return $employee->locked;
        });

        return Inertia::render('Adminland/Employee/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'employees' => $employeesCollection,
            'numberOfLockedAccounts' => $numberOfLockedAccounts->count(),
            'numberOfActiveAccounts' => $employees->count() - $numberOfLockedAccounts->count(),
        ]);
    }

    /**
     * Show the Create employee view.
     *
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Adminland/Employee/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'email' => $request->input('email'),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'permission_level' => $request->input('permission_level'),
            'send_invitation' => $request->input('send_invitation'),
        ];

        (new AddEmployeeToCompany)->execute($request);

        return response()->json([
            'company_id' => $companyId,
        ]);
    }

    /**
     * Show the Lock employee view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return mixed
     */
    public function lock(Request $request, int $companyId, int $employeeId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        if ($loggedCompany->id != $companyId) {
            return redirect('/home');
        }

        if ($employeeId == $loggedEmployee->id) {
            return redirect('/home');
        }

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->findOrFail($employeeId);
        } catch (ModelNotFoundException $e) {
            return redirect('/home');
        }

        return Inertia::render('Adminland/Employee/Lock', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Lock the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function lockAccount(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $loggedCompany->id,
            'employee_id' => $employeeId,
            'author_id' => $loggedEmployee->id,
        ];

        (new LockEmployee)->execute($request);

        return response()->json([
            'company_id' => $companyId,
        ]);
    }

    /**
     * Show the Unlock employee view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return mixed
     */
    public function unlock(Request $request, int $companyId, int $employeeId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        if ($loggedCompany->id != $companyId) {
            return redirect('/home');
        }

        if ($employeeId == $loggedEmployee->id) {
            return redirect('/home');
        }

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->findOrFail($employeeId);
        } catch (ModelNotFoundException $e) {
            return redirect('/home');
        }

        return Inertia::render('Adminland/Employee/Unlock', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Unlock the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function unlockAccount(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $loggedCompany->id,
            'employee_id' => $employeeId,
            'author_id' => $loggedEmployee->id,
        ];

        (new UnlockEmployee)->execute($request);

        return response()->json([
            'company_id' => $companyId,
        ]);
    }

    /**
     * Show the Delete employee view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return mixed
     */
    public function delete(Request $request, int $companyId, int $employeeId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        if ($loggedCompany->id != $companyId) {
            return redirect('/home');
        }

        if ($employeeId == $loggedEmployee->id) {
            return redirect('/home');
        }

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->findOrFail($employeeId);
        } catch (ModelNotFoundException $e) {
            return redirect('/home');
        }

        return Inertia::render('Adminland/Employee/Delete', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Delete the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $loggedCompany->id,
            'employee_id' => $employeeId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyEmployee)->execute($request);

        return response()->json([
            'company_id' => $companyId,
        ]);
    }
}
