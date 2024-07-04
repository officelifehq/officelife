<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRDisciplineCaseViewHelper;
use App\Models\Company\DisciplineCase;
use App\Services\Company\Employee\DisciplineCase\CreateDisciplineCase;
use App\Services\Company\Employee\DisciplineCase\DestroyDisciplineCase;
use App\Services\Company\Employee\DisciplineCase\ToggleDisciplineCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardDisciplineCasesController extends Controller
{
    /**
     * Show the list of discipline cases, opened or closed.
     *
     * @return mixed
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        return Inertia::render('Dashboard/HR/DisciplineCases/Index', [
            'data' => DashboardHRDisciplineCaseViewHelper::index($company),
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Show the list of discipline cases, opened or closed.
     *
     * @return mixed
     */
    public function closed(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        return Inertia::render('Dashboard/HR/DisciplineCases/Closed', [
            'data' => DashboardHRDisciplineCaseViewHelper::closed($company),
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Get the list of potential employees to have a discipline case.
     */
    public function search(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        $potentialEmployees = DashboardHRDisciplineCaseViewHelper::potentialEmployees(
            $company,
            $request->input('searchTerm')
        );

        return response()->json([
            'data' => $potentialEmployees,
        ]);
    }

    /**
     * Create a new discipline case.
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $case = (new CreateDisciplineCase)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $request->input('employee'),
        ]);

        return response()->json([
            'data' => DashboardHRDisciplineCaseViewHelper::dto($loggedCompany, $case),
        ]);
    }

    /**
     * Show the discipline case.
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

        return Inertia::render('Dashboard/HR/DisciplineCases/Show', [
            'data' => DashboardHRDisciplineCaseViewHelper::show($company, $case),
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Close a discipline case.
     */
    public function toggle(Request $request, int $companyId, int $caseId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $case = (new ToggleDisciplineCase)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'discipline_case_id' => $caseId,
        ]);

        return response()->json([
            'data' => DashboardHRDisciplineCaseViewHelper::dto($loggedCompany, $case),
        ]);
    }

    /**
     * Delete a discipline case.
     */
    public function destroy(Request $request, int $companyId, int $caseId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new DestroyDisciplineCase)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'discipline_case_id' => $caseId,
        ]);

        return response()->json([
            'data' => [
                'url' => route('dashboard.hr.disciplinecase.index', [
                    'company' => $loggedCompany,
                ]),
            ],
        ]);
    }
}
