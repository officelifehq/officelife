<?php

namespace App\Helpers;

use App\Models\Company\Notification;

class NotificationHelper
{
    /**
     * Return an sentence explaining what the notification contains.
     *
     * @param Notification $notification
     * @return string
     */
    public static function process(Notification $notification): string
    {
        $sentence = '';

        if ($notification->action == 'dummy_data_generated') {
            $sentence = trans('account.notification_dummy_data_generated', [
                'name' => $notification->object->{'company_name'},
            ]);
        }

        return $sentence;
    }
}
