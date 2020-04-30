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
        return $this->markdown('emails.company.invitation');
    }
}
