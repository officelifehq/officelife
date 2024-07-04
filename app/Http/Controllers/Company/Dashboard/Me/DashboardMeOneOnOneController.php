<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Dashboard\DashboardOneOnOneViewHelper;
use App\Models\Company\OneOnOneEntry;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneNote;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneTalkingPoint;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneNote;
use App\Services\Company\Employee\OneOnOne\DestroyOneOnOneTalkingPoint;
use App\Services\Company\Employee\OneOnOne\MarkOneOnOneEntryAsHappened;
use App\Services\Company\Employee\OneOnOne\ToggleOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\ToggleOneOnOneTalkingPoint;
use App\Services\Company\Employee\OneOnOne\UpdateOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\UpdateOneOnOneNote;
use App\Services\Company\Employee\OneOnOne\UpdateOneOnOneTalkingPoint;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardMeOneOnOneController extends Controller
{
    /**
     * Show the One on One entry.
     *
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function show(Request $request, int $companyId, int $entryId)
    {
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $entry = OneOnOneEntry::with('employee')
                ->with('manager')
                ->with('actionItems')
                ->with('talkingPoints')
                ->with('notes')
                ->findOrFail($entryId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        if ($entry->manager_id != $employee->id && $entry->employee_id != $employee->id) {
            return redirect('home');
        }

        $details = DashboardOneOnOneViewHelper::details($entry, $employee);

        return Inertia::render('Dashboard/OneOnOnes/Show', [
            'entry' => $details,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Mark an entry as happened.
     */
    public function markHappened(Request $request, int $companyId, int $entryId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $entry = OneOnOneEntry::findOrFail($entryId);

        if ($entry->manager_id != $employee->id && $entry->employee_id != $employee->id) {
            return redirect('home');
        }

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'one_on_one_entry_id' => $entryId,
        ];

        $newEntry = (new MarkOneOnOneEntryAsHappened)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('dashboard.oneonones.show', [
                    'company' => $company,
                    'entry' => $newEntry,
                ]),
            ],
        ], 200);
    }

    /**
     * Create a talking point.
     */
    public function storeTalkingPoint(Request $request, int $companyId, int $entryId): JsonResponse
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
     * Create an action item.
     */
    public function storeActionItem(Request $request, int $companyId, int $entryId): JsonResponse
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
     * Create a note.
     */
    public function storeNote(Request $request, int $companyId, int $entryId): JsonResponse
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
     * Update a talking point.
     */
    public function updateTalkingPoint(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
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
     * Update an action item.
     */
    public function updateActionItem(Request $request, int $companyId, int $entryId, int $actionItemId): JsonResponse
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
     * Update a note.
     */
    public function updateNote(Request $request, int $companyId, int $entryId, int $noteId): JsonResponse
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
     * Toggle a talking point.
     */
    public function toggleTalkingPoint(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
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
     * Toggle an action item.
     */
    public function toggleActionItem(Request $request, int $companyId, int $entryId, int $actionItemId): JsonResponse
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
     * Delete a talking point.
     */
    public function destroyTalkingPoint(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
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

    /**
     * Delete an action item.
     */
    public function destroyActionItem(Request $request, int $companyId, int $entryId, int $talkingPointId): JsonResponse
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

    /**
     * Delete a note.
     */
    public function destroyNote(Request $request, int $companyId, int $entryId, int $noteId): JsonResponse
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
