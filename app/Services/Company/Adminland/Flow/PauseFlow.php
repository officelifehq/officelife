<?php

namespace App\Services\Company\Adminland\Flow;

use Carbon\Carbon;
use App\Models\Company\Flow;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ScheduledAction;

class PauseFlow extends BaseService
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
     * Pause a flow and delete all future scheduled actions associated with
     * this flow.
     *
     * @param array $data
     * @return Flow
     */
    public function execute(array $data): Flow
    {
        $this->data = $data;
        $this->validate();
        $this->destroyUnprocessedScheduledActions();
        $this->pause();
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
        $actions = $this->flow->steps()->actions()
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

    private function pause(): void
    {
        $this->flow->paused = true;
        $this->flow->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'flow_paused',
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
