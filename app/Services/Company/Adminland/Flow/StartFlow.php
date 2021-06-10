<?php

namespace App\Services\Company\Adminland\Flow;

use Carbon\Carbon;
use App\Models\Company\Flow;
use App\Models\Company\Step;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ScheduledAction;

class StartFlow extends BaseService
{
    private Flow $flow;
    private array $data;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'flow_id' => 'required|integer|exists:flows,id',
        ];
    }

    /**
     * Start a flow and schedule all the actions for each step of the flow, for
     * all the employees in the company.
     * If the flow had been started in the past and put on pause, we will
     * delete all future scheduled actions and create new ones.
     * We won't touch scheduled actions that have been already processed.
     *
     * @param array $data
     * @return Flow
     */
    public function execute(array $data): Flow
    {
        $this->data = $data;
        $this->validate();
        $this->destroyUnprocessedScheduledActions();
        $this->start();
        $this->log();

        return $this->flow;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->flow = Flow::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['flow_id']);
    }

    private function destroyUnprocessedScheduledActions(): void
    {
        $actions = $this->flow->steps->actions()
            ->select('actions.id')
            ->pluck('id')
            ->toArray();

        $unprocessedScheduledActions = ScheduledAction::where('processed', false)
            ->whereIn('action_id', $actions)
            ->select('actions.id')
            ->pluck('id')
            ->toArray();

        DB::table('scheduled_actions')->whereIn('id', $unprocessedScheduledActions)->delete();
    }

    private function start(): void
    {
        $this->flow->paused = false;
        $this->flow->save();

        $employees = $this->flow->company->employees()->notLocked()->get();

        foreach ($employees as $employee) {
            (new ScheduleFlowsForEmployee)->execute([
                'company_id' => $this->data['company_id'],
                'employee_id' => $employee->id,
                'trigger' => $this->flow->trigger,
            ]);
        }
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'flow_started',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'flow_id' => $this->flow->id,
                'flow_name' => $this->flow->name,
            ]),
        ])->onQueue('low');
    }
}
