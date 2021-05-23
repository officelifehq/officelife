<?php

namespace App\Mail\Company;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Company\Company;
use Illuminate\Queue\SerializesModels;

class SendAccountCancellationToAdministratorMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Company
     */
    public $company;

    /**
     * Create a new message instance.
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->company->name.' has cancelled their account')
            ->markdown('emails.company.account-cancellation');
    }
}
