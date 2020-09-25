<?php

namespace App\Services\Logs;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\TeamLog;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogTeamAction extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'team_id' => 'required|integer|exists:teams,id',
            'author_id' => 'required|integer|exists:employees,id',
            'author_name' => 'required|string|max:255',
            'audited_at' => 'required|date',
            'action' => 'required|string|max:255',
            'objects' => 'required|json',
            'ip_address' => 'nullable|ipv4',
        ];
    }

    /**
     * Log an action that happened to the team.
     *
     * @param array $data
     *
     * @return TeamLog
     */
    public function execute(array $data): TeamLog
    {
        $this->validateRules($data);

        $author = Employee::findOrFail($data['author_id']);
        $team = Team::findOrFail($data['team_id']);

        if ($author->company_id != $team->company_id) {
            throw new ModelNotFoundException();
        }

        return TeamLog::create([
            'team_id' => $data['team_id'],
            'author_id' => $data['author_id'],
            'author_name' => $data['author_name'],
            'audited_at' => $data['audited_at'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->valueOrNull($data, 'ip_address'),
        ]);
    }
}
