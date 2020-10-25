<?php

namespace App\Notifications;

use App\Models\User\User;
use Illuminate\Notifications\Messages\MailMessage;

class EmailMessaging
{
    /**
     * Get the mail representation to verify an email.
     *
     * @param  User $user
     * @param mixed $verificationUrl
     * @return MailMessage
     */
    public static function verifyEmailMail(User $user, $verificationUrl): MailMessage
    {
        return (new MailMessage)
            ->subject(trans('mail.confirmation_email_title'))
            ->greeting(trans('mail.confirmation_email_greetings', ['name' => $user->name]))
            ->line(trans('mail.confirmation_email_intro'))
            ->line(trans('mail.confirmation_email_action'))
            ->action(trans('mail.confirmation_email_button'), $verificationUrl);
    }
}
