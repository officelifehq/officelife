<?php

namespace App\Services\Company\Group;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Group;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;

class MarkEmployeeAsParticipantOfMeeting extends BaseService
{
    private array $data;
    private Employee $employee;
    private Group $group;
    private Meeting $meeting;

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
            'meeting_id' => 'required|integer|exists:meetings,id',
            'employee_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Mark an employee as participant of a meeting.
     * When marking an employee, we should check if the employee is part of the
     * group the meeting belongs to or not.
     * If the employee is not part of the group, we should mark it as guest.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->data = $data;
        $this->validate();

        $this->attachEmployee();
        $this->log();

        return $this->employee;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $this->employee = $this->validateEmployeeBelongsToCompany($this->data);

        $this->group = Group::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['group_id']);

        $this->meeting = Meeting::where('group_id', $this->data['group_id'])
            ->findOrFail($this->data['meeting_id']);
    }

    private function attachEmployee(): void
    {
        $this->meeting->employees()->syncWithoutDetaching([
            $this->data['employee_id'] => [
                'was_a_guest' => $this->isEmployeePartOfGroup(),
            ],
        ]);
    }

    private function isEmployeePartOfGroup(): bool
    {
        return DB::table('employee_group')
            ->where('employee_id', $this->data['employee_id'])
            ->where('group_id', $this->group->id)
            ->count() == 1;
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'employee_marked_as_participant_in_meeting',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'group_id' => $this->group->id,
                'group_name' => $this->group->name,
                'employee_id' => $this->employee->id,
                'employee_name' => $this->employee->name,
                'meeting_id' => $this->meeting->id,
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $this->employee->id,
            'action' => 'employee_marked_as_participant_in_meeting',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'group_id' => $this->group->id,
                'group_name' => $this->group->name,
                'meeting_id' => $this->meeting->id,
            ]),
        ])->onQueue('low');
    }
}
