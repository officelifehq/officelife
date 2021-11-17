<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use App\Helpers\ImageHelper;
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
                'number_of_events' => (int) $case->number_of_events,
                'author' => [
                    'id' => $case->author->id,
                    'name' => $case->author->name,
                    'avatar' => ImageHelper::getAvatar($case->author),
                    'position' => (! $case->author->position) ? null : $case->author->position->title,
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $case->author,
                    ]),
                ],
                'employee' => [
                    'id' => $case->employee->id,
                    'name' => $case->employee->name,
                    'avatar' => ImageHelper::getAvatar($case->employee),
                    'position' => (! $case->employee->position) ? null : $case->employee->position->title,
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $case->employee,
                    ]),
                ],
                'url' => [
                    'show' => route('dashboard.hr.disciplinecase.show', [
                        'company' => $loggedCompany,
                        'case' => $case,
                    ]),
                ],
            ],
        ]);
    }

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

        return Inertia::render('Dashboard/HR/DisciplineCases/Index', [
            'data' => DashboardHRDisciplineCaseViewHelper::index($company),
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
