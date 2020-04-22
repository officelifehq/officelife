<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use App\Models\Company\Flow;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Services\Company\Adminland\Flow\CreateFlow;
use App\Services\Company\Adminland\Flow\AddStepToFlow;
use App\Services\Company\Adminland\Flow\AddActionToStep;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Company\Flow\Flow as FlowResource;

class AdminFlowController extends Controller
{
    /**
     * Show the list of positions.
     *
     * @return Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $flows = FlowResource::collection(
            $company->flows()->orderBy('created_at', 'desc')->get()
        );

        return Inertia::render('Adminland/Flow/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'flows' => $flows,
        ]);
    }

    /**
     * Display the detail of a flow.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $flowId
     *
     * @return Response
     */
    public function show(Request $request, int $companyId, int $flowId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $flow = Flow::where('company_id', $company->id)
                ->findOrFail($flowId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Adminland/Flow/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'flow' => new FlowResource($flow),
        ]);
    }

    /**
     * Show the Create flow view.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Adminland/Flow/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Save the flow.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function store(Request $request, int $companyId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'name' => $request->get('name'),
            'type' => $request->get('type'),
        ];

        $flow = (new CreateFlow)->execute($data);

        // add steps for the flow
        foreach ($request->get('steps') as $step) {
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
