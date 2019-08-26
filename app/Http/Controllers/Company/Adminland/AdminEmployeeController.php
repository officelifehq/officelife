<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Services\Company\Adminland\Employee\DestroyEmployee;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;
use App\Http\Resources\Company\Employee\EmployeeList as EmployeeListResource;

class AdminEmployeeController extends Controller
{
    /**
     * Show the list of employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employees = $company->employees()->with('teams')
            ->with('status')
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Adminland/Employee/Index', [
            'notifications' => Auth::user()->getLatestNotifications($company),
            'employees' => EmployeeListResource::collection($employees),
        ]);
    }

    /**
     * Show the Create employee view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Adminland/Employee/Create', [
            'notifications' => Auth::user()->getLatestNotifications($company),
        ]);
    }

    /**
     * Create the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'email' => $request->get('email'),
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'permission_level' => $request->get('permission_level'),
            'send_invitation' => $request->get('send_invitation'),
        ];

        (new AddEmployeeToCompany)->execute($request);

        return response()->json([
            'company_id' => $companyId,
        ]);
    }

    /**
     * Delete the employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $companyId, $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'employee_id' => $employeeId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyEmployee)->execute($request);

        return redirect(tenant('/account/employees'));
    }
}
