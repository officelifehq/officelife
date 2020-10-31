<?php

namespace App\Http\Controllers\Company\Employee;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Country;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Place\CreatePlace;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeEditController extends Controller
{
    /**
     * Show the employee edit address page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function address(Request $request, int $companyId, int $employeeId)
    {
        try {
            $employee = Employee::where('company_id', $companyId)
                ->findOrFail($employeeId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $this->asUser(Auth::user())
                ->forEmployee($employee)
                ->forCompanyId($companyId)
                ->asPermissionLevel(config('officelife.permission_level.hr'))
                ->canAccessCurrentPage();
        } catch (\Exception $e) {
            return redirect('/home');
        }

        $countries = Country::select('id', 'name')->get();
        $countriesCollection = collect([]);
        foreach ($countries as $country) {
            $countriesCollection->push([
                'value' => $country->id,
                'label' => $country->name,
            ]);
        }

        return Inertia::render('Employee/Edit/Address', [
            'employee' => $employee->toObject(),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'countries' => $countriesCollection,
        ]);
    }

    /**
     * Update the information about the employee's address.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function updateAddress(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'street' => $request->input('street'),
            'city' => $request->input('city'),
            'province' => $request->input('state'),
            'postal_code' => $request->input('postal_code'),
            'country_id' => $request->input('country_id'),
            'placable_id' => $employeeId,
            'placable_type' => 'App\Models\Company\Employee',
            'is_active' => true,
        ];

        (new CreatePlace)->execute($data);

        return response()->json([
            'company_id' => $companyId,
        ], 200);
    }
}
