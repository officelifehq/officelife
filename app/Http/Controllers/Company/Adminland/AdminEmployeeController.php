<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Adminland\Employee\DestroyEmployee;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class AdminEmployeeController extends Controller
{
    /**
     * Show the list of employees.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Cache::get('cachedCompanyObject');
        $employee = Cache::get('cachedEmployeeObject');
        $employees = EmployeeResource::collection(
            $company->employees()->orderBy('created_at', 'desc')->get()
        );

        return Inertia::render('Adminland/Employee/Index', [
            'company' => $company,
            'employee' => new EmployeeResource($employee),
            'notifications' => auth()->user()->getLatestNotifications($company),
            'employees' => $employees,
        ]);
    }

    /**
     * Show the Create employee view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Cache::get('cachedCompanyObject');

        return View::component('CreateAccountEmployee', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'notifications' => auth()->user()->getLatestNotifications($company),
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
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
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
        $request = [
            'company_id' => $companyId,
            'employee_id' => $employeeId,
            'author_id' => auth()->user()->id,
        ];

        (new DestroyEmployee)->execute($request);

        return redirect(tenant('/account/employees'));
    }
}
