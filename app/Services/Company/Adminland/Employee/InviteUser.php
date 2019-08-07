<?php

namespace App\Services\Company\Adminland\Employee;

use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Mail;
use App\Mail\Company\InviteEmployeeToBecomeUser;
use App\Exceptions\InvitationAlreadyUsedException;

/**
 * This service invites an employee by email.
 * This email will be used to create a User account for this employee - or
 * will let the user link an existing account if it already exists.
 */
class InviteUser extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'employee_id' => 'required|integer|exists:employees,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Send an invitation email to the employee so he can become a user.
     *
     * @param array $data
     * @return Employee
     */
    public function execute(array $data) : Employee
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $employee = $this->inviteEmployee($data);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'employee_invited_to_become_user',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'employee_id' => $employee->id,
                'employee_email' => $employee->email,
                'employee_first_name' => $employee->first_name,
                'employee_last_name' => $employee->last_name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $employee;
    }

    /**
     * Send the email.
     *
     * @param array $data
     * @return Employee
     */
    private function inviteEmployee($data) : Employee
    {
        $employee = Employee::find($data['employee_id']);

        if ($employee->invitationAlreadyAccepted()) {
            throw new InvitationAlreadyUsedException();
        }

        $employee->invitation_link = Str::uuid()->toString();
        $employee->save();

        Mail::to($employee->email)
            ->queue(new InviteEmployeeToBecomeUser($employee));

        return $employee;
    }
}
