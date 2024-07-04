<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\Flow;
use App\Services\Company\Adminland\Flow\AddActionToStep;
use App\Services\Company\Adminland\Flow\AddStepToFlow;
use App\Services\Company\Adminland\Flow\CreateFlow;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Inertia\Response;

class AdminFlowController extends Controller
{
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $flows = $company->flows()->orderBy('created_at', 'desc')->get();

        return Inertia::render('Adminland/Flow/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'flows' => $flows,
        ]);
    }

    /**
     * Display the detail of a flow.
     *
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $flowId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $flow = Flow::where('company_id', $company->id)
                ->with('steps')
                ->findOrFail($flowId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Adminland/Flow/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'flow' => $flow,
        ]);
    }

    /**
     * Show the Create flow view.
     */
    public function create(): Response
    {
        return Inertia::render('Adminland/Flow/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Save the flow.
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
            'type' => $request->input('type'),
        ];

        $flow = (new CreateFlow)->execute($data);

        // add steps for the flow
        foreach ($request->input('steps') as $step) {
            $newStep = (new AddStepToFlow)->execute([
                'company_id' => $companyId,
                'author_id' => $loggedEmployee->id,
                'flow_id' => $flow->id,
                'number' => $step['number'],
                'unit_of_time' => $step['frequency'],
                'modifier' => $step['type'],
            ]);

            // for each step, add actions
            foreach ($step['actions'] as $action) {
                (new AddActionToStep)->execute([
                    'company_id' => $companyId,
                    'author_id' => $loggedEmployee->id,
                    'flow_id' => $flow->id,
                    'step_id' => $newStep->id,
                    'type' => $action['type'],
                    'recipient' => $action['target'],
                    'specific_recipient_information' => json_encode($action),
                ]);
            }
        }

        return response()->json([
            'company_id' => $companyId,
        ]);
    }
}
