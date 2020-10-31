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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\DashboardOneOnOneViewHelper;
use App\Services\Company\Employee\OneOnOne\MarkOneOnOneEntryAsHappened;

class DashboardMeOneOnOneController extends Controller
{
    /**
     * Show the One on One entry.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
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

        $details = DashboardOneOnOneViewHelper::details($entry);

        return Inertia::render('Dashboard/OneOnOnes/Show', [
            'entry' => $details,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Mark an entry as happened.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $entryId
     * @return JsonResponse
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
}
