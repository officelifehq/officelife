<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\DisciplineCase\CreateDisciplineEvent;
use App\Services\Company\Employee\DisciplineCase\DestroyDisciplineEvent;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRDisciplineEventViewHelper;

class DashboardDisciplineEventsController extends Controller
{
    /**
     * Create a new discipline event.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $caseId
     *
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $caseId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $event = (new CreateDisciplineEvent)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'discipline_case_id' => $caseId,
            'happened_at' => Carbon::parse($request->input('happened_at'))->format('Y-m-d'),
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'data' => DashboardHRDisciplineEventViewHelper::dto($loggedCompany, $event->case, $event),
        ], 201);
    }

    /**
     * Delete a discipline event.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $caseId
     * @param int $eventId
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $caseId, int $eventId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new DestroyDisciplineEvent)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'discipline_case_id' => $caseId,
            'discipline_event_id' => $eventId,
        ]);

        return response()->json([
            'data' => true,
        ]);
    }
}
