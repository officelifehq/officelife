<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\OneOnOneEntry;
use App\Http\ViewHelpers\Dashboard\DashboardOneOnOneViewHelper;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneTalkingPoint;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneTalkingPoint;

class DashboardMeOneOnOneController extends Controller
{
    /**
     * Show the One on One entry.
     *
     * @return Response
     */
    public function show(Request $request, int $companyId, int $entryId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $entry = OneOnOneEntry::where('manager_id', $employee->id)
            ->orWhere('employee_id', $employee->id)
            ->with('employee')
            ->with('manager')
            ->with('actionItems')
            ->with('talkingPoints')
            ->with('notes')
            ->findOrFail($entryId);

        $details = DashboardOneOnOneViewHelper::details($entry);

        return Inertia::render('Dashboard/OneOnOnes/Show', [
            'entry' => $details,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Create a talking point.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeTalkingPoint(Request $request, int $companyId, int $entryId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $entry = OneOnOneEntry::where('manager_id', $request->input('manager_id'))
            ->where('employee_id', $request->input('employee_id'))
            ->findOrFail($entryId);

        $request = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'description' => $request->input('description'),
        ];

        $talkingPoint = (new CreateOneOnOneTalkingPoint)->execute($request);

        return response()->json([
            'data' => [
                'id' => $talkingPoint->id,
                'description' => $talkingPoint->description,
                'checked' => $talkingPoint->checked,
            ],
        ], 200);
    }

    /**
     * Create an action item.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeActionItem(Request $request, int $companyId, int $entryId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $entry = OneOnOneEntry::where('manager_id', $request->input('manager_id'))
        ->where('employee_id', $request->input('employee_id'))
        ->findOrFail($entryId);

        $request = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'description' => $request->input('description'),
        ];

        $actionItem = (new CreateOneOnOneActionItem)->execute($request);

        return response()->json([
            'data' => [
                'id' => $actionItem->id,
                'description' => $actionItem->description,
                'checked' => $actionItem->checked,
            ],
        ], 200);
    }

    /**
     * Delete a talking point.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroyTalkingPoint(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_talking_point_id' => $talkingPointId,
        ];

        (new DestroyOneOnOneTalkingPoint)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Delete an action item.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroyActionItem(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_talking_point_id' => $talkingPointId,
        ];

        (new DestroyOneOnOneActionItem)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
