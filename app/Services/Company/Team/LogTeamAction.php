<?php

namespace App\Services\Company\Team;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\TeamLog;

class LogTeamAction extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'team_id' => 'required|integer|exists:teams,id',
            'action' => 'required|string|max:255',
            'objects' => 'required|json',
            'ip_address' => 'nullable|ipv4',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Log an action that happened to the team.
     *
     * @param array $data
     * @return TeamLog
     */
    public function execute(array $data): TeamLog
    {
        $this->validate($data);

        Team::where('company_id', $data['company_id'])
            ->findOrFail($data['team_id']);

        return TeamLog::create([
            'company_id' => $data['company_id'],
            'team_id' => $data['team_id'],
            'action' => $data['action'],
            'objects' => $data['objects'],
            'ip_address' => $this->nullOrValue($data, 'ip_address'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
