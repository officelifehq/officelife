<?php

namespace App\Mail\Company;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;

class InviteEmployeeToBecomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Employee
     */
    public $employee;

    /**
     * Create a new message instance.
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(trans('mail.invite_employee_to_become_user'))
            ->markdown('emails.company.invitation');
    }
}
