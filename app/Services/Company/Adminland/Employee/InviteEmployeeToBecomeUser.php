<?php

namespace App\Services\Company\Adminland\Employee;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\UserAlreadyInvitedException;
use App\Mail\Company\InviteEmployeeToBecomeUserMail;

/**
 * This service invites an employee by email.
 * This email will be used to create a User account for this employee - or
 * will let the user link an existing account if it already exists.
 */
class InviteEmployeeToBecomeUser extends BaseService
{
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
            'employee_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Send an invitation email to the employee so he can become a user.
     *
     * @param array $data
     *
     * @return Employee
     */
    public function execute(array $data): Employee
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $employee = $this->validateEmployeeBelongsToCompany($data);

        $this->inviteEmployee($employee);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_invited_to_become_user',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'employee_id' => $employee->id,
                'employee_email' => $employee->email,
                'employee_first_name' => $employee->first_name,
                'employee_last_name' => $employee->last_name,
            ]),
        ])->onQueue('low');

        return $employee;
    }

    /**
     * Send the email.
     *
     * @param Employee $employee
     */
    private function inviteEmployee(Employee $employee): void
    {
        if ($employee->invitation_used_at) {
            throw new UserAlreadyInvitedException();
        }

        Employee::where('id', $employee->id)->update([
            'invitation_link' => Str::uuid()->toString(),
        ]);

        Mail::to($employee->email)
            ->queue(new InviteEmployeeToBecomeUserMail($employee->refresh()));
    }
}
