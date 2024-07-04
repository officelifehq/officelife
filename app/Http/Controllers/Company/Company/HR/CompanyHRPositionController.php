<?php

namespace App\Http\Controllers\Company\Company\HR;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\HR\CompanyHRPositionShowViewHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyHRPositionController extends Controller
{
    /**
     * Show the HR main page on the Company tab.
     *
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $positionId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $position = Position::where('company_id', $company->id)
                ->findOrFail($positionId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $employeesCount = Employee::where('company_id', $company->id)
            ->where('position_id', $position->id)
            ->count();

        if ($employeesCount <= 0) {
            return redirect('home');
        }

        $data = CompanyHRPositionShowViewHelper::show($company, $position);

        return Inertia::render('Company/HR/Positions/Show', [
            'data' => $data,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
