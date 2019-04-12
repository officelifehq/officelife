<?php

namespace App\Http\Controllers\Company\Adminland;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\Adminland\Employee\DestroyEmployee;
use App\Services\Adminland\Company\AddEmployeeToCompany;
use App\Http\Resources\Company\Position\Position as PositionResource;

class PositionController extends Controller
{
    /**
     * Show the list of positions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Cache::get('currentCompany');
        $positions = PositionResource::collection(
            $company->positions()->orderBy('title', 'asc')->get()
        );

        return View::component('ShowAccountPositions', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'positions' => $positions,
        ]);
    }

    /**
     * Show the Create employee view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Cache::get('currentCompany');

        return View::component('CreateAccountEmployee', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
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
