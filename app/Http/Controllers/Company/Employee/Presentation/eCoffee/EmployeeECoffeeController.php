<?php

namespace App\Http\Controllers\Company\Employee\Presentation\eCoffee;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Employee\EmployeeECoffeeViewHelper;

class EmployeeECoffeeController extends Controller
{
    /**
     * Show the list of current and past eCoffee sessions.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return mixed
     */
    public function index(Request $request, int $companyId, int $employeeId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $employee = Employee::where('company_id', $companyId)
                ->where('id', $employeeId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // information about the eCoffee sessions
        $eCoffees = EmployeeECoffeeViewHelper::index($employee, $company);

        return Inertia::render('Employee/ECoffee/Index', [
            'employee' => [
                'id' => $employeeId,
                'name' => $employee->name,
            ],
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'eCoffees' => $eCoffees,
        ]);
    }
}
