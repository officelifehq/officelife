<?php

namespace App\Http\Controllers\Company\Dashboard\OneOnOne;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Company\OneOnOneEntry;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneNote;
use App\Services\Company\Employee\OneOnOne\UpdateOneOnOneNote;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneNote;

class DashboardMeOneOnOneNoteController extends Controller
{
    /**
     * Create a note.
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
            'note' => $request->input('description'),
        ];

        $note = (new CreateOneOnOneNote)->execute($data);

        return response()->json([
            'data' => [
                'id' => $note->id,
                'note' => $note->note,
            ],
        ], 200);
    }

    /**
     * Update a note.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @param int $noteId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $entryId, int $noteId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_note_id' => $noteId,
            'note' => $request->input('description'),
        ];

        $note = (new UpdateOneOnOneNote)->execute($data);

        return response()->json([
            'data' => [
                'id' => $note->id,
                'note' => $note->note,
            ],
        ], 200);
    }

    /**
     * Delete a note.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @param int $noteId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $entryId, int $noteId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
            'one_on_one_note_id' => $noteId,
        ];

        (new DestroyOneOnOneNote)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
