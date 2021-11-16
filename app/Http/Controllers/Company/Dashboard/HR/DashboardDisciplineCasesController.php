<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\DisciplineCase\CreateDisciplineCase;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRDisciplineCaseViewHelper;

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
     * Get the list of potential employees to have a discipline case.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return JsonResponse
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
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return JsonResponse
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
            'data' => [
                'id' => $case->id,
                'name' => $case->name,
            ],
        ]);
    }
}
