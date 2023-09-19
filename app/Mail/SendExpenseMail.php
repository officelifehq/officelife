<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendExpenseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
		$base64string = $this->mailData['attachment'];
		if (strpos($base64string, ',') !== false) {
            @list($encode, $base64string) = explode(',', $base64string);
        }

        $base64data = base64_decode($base64string, true);

        $mail = $this
            ->from(env('MAIL_FROM_ADDRESS', 'hello@example.com'))
            ->subject("Expense: " . $this->mailData['name'])
			->html($this->mailData['title'] . "\n Bill Amount: ". $this->mailData['amount']);
        if (isset($this->mailData['attachment'])) {
			$fileName = "Invoice-" . str_replace(" ", "", $this->mailData['amount']);
            $mail = $mail->attachData($base64data, $fileName . ".pdf", [ 'mime' => 'application/pdf']);
        }

        return $mail;
    }
}
