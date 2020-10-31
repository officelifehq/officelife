<?php

namespace App\Http\Controllers\Company\Dashboard\OneOnOne;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Company\OneOnOneEntry;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\ToggleOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\UpdateOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneActionItem;

class DashboardMeOneOnOneActionItemController extends Controller
{
    /**
     * Create an action item.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $entryId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        OneOnOneEntry::where('manager_id', $request->input('manager_id'))
            ->where('employee_id', $request->input('employee_id'))
            ->findOrFail($entryId);

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'description' => $request->input('description'),
        ];

        $actionItem = (new CreateOneOnOneActionItem)->execute($data);

        return response()->json([
            'data' => [
                'id' => $actionItem->id,
                'description' => $actionItem->description,
                'checked' => $actionItem->checked,
            ],
        ], 200);
    }

    /**
     * Update an action item.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @param int $actionItemId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $entryId, int $actionItemId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_action_item_id' => $actionItemId,
            'description' => $request->input('description'),
        ];

        $actionItem = (new UpdateOneOnOneActionItem)->execute($data);

        return response()->json([
            'data' => [
                'id' => $actionItem->id,
                'description' => $actionItem->description,
                'checked' => $actionItem->checked,
            ],
        ], 200);
    }

    /**
     * Toggle an action item.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @param int $actionItemId
     * @return JsonResponse
     */
    public function toggle(Request $request, int $companyId, int $entryId, int $actionItemId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_action_item_id' => $actionItemId,
        ];

        $actionItem = (new ToggleOneOnOneActionItem)->execute($data);

        return response()->json([
            'data' => [
                'id' => $actionItem->id,
                'description' => $actionItem->description,
                'checked' => $actionItem->checked,
            ],
        ], 200);
    }

    /**
     * Delete an action item.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @param int $talkingPointId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_action_item_id' => $talkingPointId,
        ];

        (new DestroyOneOnOneActionItem)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
