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

        if ($notification->action == 'employee_attached_to_recent_ship') {
            $sentence = trans('account.notification_employee_attached_to_recent_ship', [
                'title' => $notification->object->{'ship_title'},
            ]);
        }

        if ($notification->action == 'task_assigned') {
            $sentence = trans('account.notification_task_assigned', [
                'title' => $notification->object->{'title'},
                'name' => $notification->object->{'author_name'},
            ]);
        }

        if ($notification->action == 'expense_assigned_for_validation') {
            $sentence = trans('account.notification_expense_assigned_for_validation', [
                'name' => $notification->object->{'name'},
            ]);
        }

        if ($notification->action == 'expense_accepted_by_manager') {
            $sentence = trans('account.notification_expense_accepted_by_manager', [
                'title' => $notification->object->{'title'},
            ]);
        }

        if ($notification->action == 'expense_rejected_by_manager') {
            $sentence = trans('account.notification_expense_rejected_by_manager', [
                'title' => $notification->object->{'title'},
            ]);
        }

        if ($notification->action == 'employee_allowed_to_manage_expenses') {
            $sentence = trans('account.notification_employee_allowed_to_manage_expenses', []);
        }

        return $sentence;
    }
}
