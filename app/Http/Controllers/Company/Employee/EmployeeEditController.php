<?php

namespace App\Http\Controllers\Company\Employee;

use Carbon\Carbon;
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
use App\Services\Company\Employee\Birthdate\SetBirthdate;
use App\Services\Company\Employee\PersonalDetails\SetPersonalDetails;

class EmployeeEditController extends Controller
{
    /**
     * Show the employee edit page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function show(Request $request, int $companyId, int $employeeId)
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

        return Inertia::render('Employee/Edit', [
            'employee' => $employee->toObject(),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Update the information about the employee's address.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return JsonResponse
     */
    public function update(Request $request, $companyId, $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
        ];

        (new SetPersonalDetails)->execute($data);

        $date = Carbon::createFromDate($request->input('year'), $request->input('month'), $request->input('day'));

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'date' => $date->format('Y-m-d'),
            'year' => intval($request->input('year')),
            'month' => intval($request->input('month')),
            'day' => intval($request->input('day')),
        ];

        (new SetBirthdate)->execute($request);

        return response()->json([
            'company_id' => $companyId,
        ], 200);
    }

    /**
     * Show the employee edit address page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function address(Request $request, int $companyId, int $employeeId): Response
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
     *
     * @return JsonResponse
     */
    public function updateAddress(Request $request, $companyId, $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'street' => $request->get('street'),
            'city' => $request->get('city'),
            'province' => $request->get('state'),
            'postal_code' => $request->get('postal_code'),
            'country_id' => $request->get('country_id'),
            'placable_id' => $employeeId,
            'placable_type' => 'App\Models\Company\Employee',
            'is_active' => true,
        ];

        (new CreatePlace)->execute($request);

        return response()->json([
            'company_id' => $companyId,
        ], 200);
    }
}
