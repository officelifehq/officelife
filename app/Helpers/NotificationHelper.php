<?php

namespace App\Helpers;

use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Models\Company\Notification;

class NotificationHelper
{
    /**
     * Returns the notifications for this employee.
     *
     * @param Employee $employee
     *
     * @return Collection
     */
    public static function getNotifications(Employee $employee): Collection
    {
        $notifs = $employee->notifications()
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $notificationCollection = collect([]);
        foreach ($notifs as $notification) {
            $notificationCollection->push([
                'id' => $notification->id,
                'action' => $notification->action,
                'localized_content' => $notification->content,
                'read' => $notification->read,
            ]);
        }

        return $notificationCollection;
    }

    /**
     * Return an sentence explaining what the notification contains.
     *
     * @param Notification $notification
     *
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

        if ($notification->action == 'employee_added_to_company') {
            $sentence = trans('account.notification_employee_added_to_company', [
                'name' => $notification->object->{'company_name'},
            ]);
        }

        if ($notification->action == 'employee_added_to_team') {
            $sentence = trans('account.notification_employee_added_to_team', [
                'name' => $notification->object->{'team_name'},
            ]);
        }

        if ($notification->action == 'employee_removed_from_team') {
            $sentence = trans('account.notification_employee_removed_from_team', [
                'name' => $notification->object->{'team_name'},
            ]);
        }

        if ($notification->action == 'team_lead_set') {
            $sentence = trans('account.notification_team_lead_set', [
                'name' => $notification->object->{'team_name'},
            ]);
        }

        if ($notification->action == 'team_lead_removed') {
            $sentence = trans('account.notification_team_lead_removed', [
                'name' => $notification->object->{'team_name'},
            ]);
        }

        return $sentence;
    }
}
