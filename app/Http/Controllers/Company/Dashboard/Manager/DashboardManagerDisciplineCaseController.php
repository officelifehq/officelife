<?php

namespace App\Http\Controllers\Company\Dashboard\Manager;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\DirectReport;
use App\Models\Company\DisciplineCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRDisciplineCaseViewHelper;

class DashboardManagerDisciplineCaseController extends Controller
{
    /**
     * Show the discipline case.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $caseId
     */
    public function show(Request $request, int $companyId, int $caseId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $case = DisciplineCase::where('company_id', $company->id)
                ->findOrFail($caseId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // is the user a manager?
        $directReports = DirectReport::where('company_id', $company->id)
            ->where('manager_id', $employee->id)
            ->with('directReport')
            ->with('directReport.expenses')
            ->get();

        if ($directReports->count() == 0) {
            return redirect('home');
        }

        // can the manager see this case?
        if (! $employee->isManagerOf($case->employee->id)) {
            return redirect('home');
        }

        return Inertia::render('Dashboard/HR/DisciplineCases/Show', [
            'data' => DashboardHRDisciplineCaseViewHelper::show($company, $case),
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
