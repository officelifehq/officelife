<?php

namespace App\Services\Company\Team;

use App\Jobs\LogAccountAudit;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Employee;
use App\Models\Company\Team;
use App\Services\BaseService;
use Carbon\Carbon;

class UnsetTeamLead extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'team_id' => 'required|integer|exists:teams,id',
        ];
    }

    /**
     * Remove the team's leader.
     */
    public function execute(array $data): Team
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $team = $this->validateTeamBelongsToCompany($data);

        $oldTeamLeader = $team->leader;

        $team->team_leader_id = null;
        $team->save();

        $this->addNotification($oldTeamLeader, $team);

        $this->log($data, $oldTeamLeader, $team);

        return $team;
    }

    /**
     * Add a notification in the UI for the employee who has been demoted.
     */
    private function addNotification(Employee $employee, Team $team): void
    {
        NotifyEmployee::dispatch([
            'employee_id' => $employee->id,
            'action' => 'team_lead_removed',
            'objects' => json_encode([
                'team_name' => $team->name,
            ]),
        ])->onQueue('low');
    }

    /**
     * Log the information in the audit logs.
     */
    private function log(array $data, Employee $oldTeamLeader, Team $team): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_leader_removed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_leader_name' => $oldTeamLeader->name,
                'team_name' => $team->name,
            ]),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'team_leader_removed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_leader_name' => $oldTeamLeader->name,
            ]),
        ])->onQueue('low');
    }
}
