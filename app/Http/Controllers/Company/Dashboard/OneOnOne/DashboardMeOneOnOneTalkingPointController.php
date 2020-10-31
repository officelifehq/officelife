<?php

namespace App\Http\Controllers\Company\Dashboard\OneOnOne;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Company\OneOnOneEntry;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneTalkingPoint;
use App\Services\Company\Employee\OneOnOne\ToggleOneOnOneTalkingPoint;
use App\Services\Company\Employee\OneOnOne\UpdateOneOnOneTalkingPoint;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneTalkingPoint;

class DashboardMeOneOnOneTalkingPointController extends Controller
{
    /**
     * Create a talking point.
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

        $talkingPoint = (new CreateOneOnOneTalkingPoint)->execute($data);

        return response()->json([
            'data' => [
                'id' => $talkingPoint->id,
                'description' => $talkingPoint->description,
                'checked' => $talkingPoint->checked,
            ],
        ], 200);
    }

    /**
     * Update a talking point.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @param int $talkingPointId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_talking_point_id' => $talkingPointId,
            'description' => $request->input('description'),
        ];

        $talkingPoint = (new UpdateOneOnOneTalkingPoint)->execute($data);

        return response()->json([
            'data' => [
                'id' => $talkingPoint->id,
                'description' => $talkingPoint->description,
                'checked' => $talkingPoint->checked,
            ],
        ], 200);
    }

    /**
     * Toggle a talking point.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @param int $talkingPointId
     * @return JsonResponse
     */
    public function toggle(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_talking_point_id' => $talkingPointId,
        ];

        $talkingPoint = (new ToggleOneOnOneTalkingPoint)->execute($data);

        return response()->json([
            'data' => [
                'id' => $talkingPoint->id,
                'description' => $talkingPoint->description,
                'checked' => $talkingPoint->checked,
            ],
        ], 200);
    }

    /**
     * Delete a talking point.
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
            'one_on_one_talking_point_id' => $talkingPointId,
        ];

        (new DestroyOneOnOneTalkingPoint)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
