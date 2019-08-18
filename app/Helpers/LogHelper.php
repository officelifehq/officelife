<?php

namespace App\Helpers;

use App\Models\Company\AuditLog;

class LogHelper
{
    /**
     * Return an sentence explaining what the log contains.
     * A log is stored in a json file and needs some kind of processing to make
     * it understandable by a human.
     *
     * @param AuditLog $log
     * @return string
     */
    public static function processLog(AuditLog $log) : string
    {
        $sentence = '';

        if ($log->action == 'account_created') {
            $sentence = trans('account.log_account_created');
        }

        if ($log->action == 'employee_added_to_company') {
            $sentence = trans('account.log_employee_added_to_company', [
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'team_created') {
            $sentence = trans('account.log_team_created', [
                'name' => $log->object->{'team_name'},
            ]);
        }

        if ($log->action == 'employee_added_to_team') {
            $sentence = trans('account.log_employee_added_to_team', [
                'employee' => $log->object->{'employee_name'},
                'team' => $log->object->{'team_name'},
            ]);
        }

        if ($log->action == 'employee_removed_from_team') {
            $sentence = trans('account.log_employee_removed_from_team', [
                'employee' => $log->object->{'employee_name'},
                'team' => $log->object->{'team_name'},
            ]);
        }

        if ($log->action == 'employee_updated') {
            $sentence = trans('account.log_employee_updated', [
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'employee_updated_hiring_information') {
            $sentence = trans('account.log_employee_updated_hiring_information', [
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'manager_assigned') {
            $sentence = trans('account.log_manager_assigned', [
                'manager' => $log->object->{'manager_name'},
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'manager_unassigned') {
            $sentence = trans('account.log_manager_unassigned', [
                'manager' => $log->object->{'manager_name'},
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'employee_invited_to_become_user') {
            $sentence = trans('account.log_employee_invited_to_become_user', [
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'position_created') {
            $sentence = trans('account.log_position_created', [
                'name' => $log->object->{'position_title'},
            ]);
        }

        if ($log->action == 'position_updated') {
            $sentence = trans('account.log_position_updated', [
                'former_name' => $log->object->{'position_old_title'},
                'new_name' => $log->object->{'position_title'},
            ]);
        }

        if ($log->action == 'position_destroyed') {
            $sentence = trans('account.log_position_destroyed', [
                'name' => $log->object->{'position_title'},
            ]);
        }

        if ($log->action == 'position_assigned') {
            $sentence = trans('account.log_position_assigned', [
                'name' => $log->object->{'position_title'},
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'position_removed') {
            $sentence = trans('account.log_position_removed', [
                'name' => $log->object->{'position_title'},
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'flow_created') {
            $sentence = trans('account.log_flow_created', [
                'name' => $log->object->{'flow_name'},
            ]);
        }

        if ($log->action == 'employee_worklog_logged') {
            $sentence = trans('account.log_employee_worklog_logged');
        }

        if ($log->action == 'employee_status_created') {
            $sentence = trans('account.log_employee_status_created', [
                'name' => $log->object->{'employee_status_name'},
            ]);
        }

        if ($log->action == 'employee_status_updated') {
            $sentence = trans('account.log_employee_status_updated', [
                'former_name' => $log->object->{'employee_status_old_name'},
                'new_name' => $log->object->{'employee_status_new_name'},
            ]);
        }

        if ($log->action == 'employee_status_destroyed') {
            $sentence = trans('account.log_employee_status_destroyed', [
                'name' => $log->object->{'employee_status_name'},
            ]);
        }

        if ($log->action == 'employee_status_assigned') {
            $sentence = trans('account.log_employee_status_assigned', [
                'name' => $log->object->{'employee_status_name'},
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        if ($log->action == 'employee_status_removed') {
            $sentence = trans('account.log_employee_status_removed', [
                'name' => $log->object->{'employee_status_name'},
                'employee' => $log->object->{'employee_name'},
            ]);
        }

        return $sentence;
    }
}
