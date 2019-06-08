<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Models\Company\Flow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Adminland\Flow\CreateFlow;
use App\Services\Company\Adminland\Flow\AddStepToFlow;
use App\Services\Company\Adminland\Flow\AddActionToStep;
use App\Http\Resources\Company\Flow\Flow as FlowResource;

class AdminFlowController extends Controller
{
    /**
     * Show the list of positions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = Cache::get('currentCompany');
        $flows = FlowResource::collection(
            $company->flows()->orderBy('created_at', 'desc')->get()
        );

        return View::component('ShowAccountFlows', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'flows' => $flows,
        ]);
    }

    /**
     * Display the detail of a flow.
     *
     * @param int $companyId
     * @param int $flowId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $companyId, int $flowId)
    {
        $company = Cache::get('currentCompany');
        $flow = Flow::findOrFail($flowId);

        return View::component('ShowAccountFlow', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
            'flow' => new FlowResource($flow),
        ]);
    }

    /**
     * Show the Create flow view.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = Cache::get('currentCompany');

        return View::component('CreateAccountFlow', [
            'company' => $company,
            'user' => auth()->user()->getEmployeeObjectForCompany($company),
        ]);
    }

    /**
     * Save the flow.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $companyId)
    {
        $data = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
            'name' => $request->get('name'),
            'type' => $request->get('type'),
        ];

        $flow = (new CreateFlow)->execute($data);

        // add steps for the flow
        foreach ($request->get('steps') as $step) {
            $newStep = (new AddStepToFlow)->execute([
                'company_id' => $companyId,
                'author_id' => auth()->user()->id,
                'flow_id' => $flow->id,
                'number' => $step['number'],
                'unit_of_time' => $step['frequency'],
                'modifier' => $step['type'],
            ]);

            // for each step, add actions
            foreach ($step['actions'] as $action) {
                (new AddActionToStep)->execute([
                    'company_id' => $companyId,
                    'author_id' => auth()->user()->id,
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
