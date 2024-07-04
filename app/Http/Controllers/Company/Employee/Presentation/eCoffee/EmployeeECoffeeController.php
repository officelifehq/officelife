<?php

namespace App\Http\Controllers\Company\Employee\Presentation\eCoffee;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeECoffeeViewHelper;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeECoffeeController extends Controller
{
    /**
     * Show the list of current and past eCoffee sessions.
     *
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
