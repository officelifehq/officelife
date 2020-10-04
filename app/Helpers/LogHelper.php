<?php

namespace App\Helpers;

use App\Models\Company\TeamLog;
use App\Models\Company\AuditLog;
use App\Models\Company\EmployeeLog;

class LogHelper
{
    /**
     * Return an sentence explaining what the log contains.
     * A log is stored in a json file and needs some kind of processing to make
     * it understandable by a human.
     *
     * @param AuditLog $log
     *
     * @return string
     */
    public static function processAuditLog(AuditLog $log): string
    {
        $sentence = '';

        switch ($log->action) {
            case 'account_created':
                $sentence = trans('account.log_account_created');
                break;

            case 'employee_added_to_company':
                $sentence = trans('account.log_employee_added_to_company', [
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_destroyed':
                $sentence = trans('account.log_employee_destroyed', [
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_locked':
                $sentence = trans('account.log_employee_locked', [
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_unlocked':
                $sentence = trans('account.log_employee_unlocked', [
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'team_created':
                $sentence = trans('account.log_team_created', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'team_updated':
                $sentence = trans('account.log_team_updated', [
                    'old_name' => $log->object->{'team_old_name'},
                    'new_name' => $log->object->{'team_new_name'},
                ]);
                break;

            case 'team_destroyed':
                $sentence = trans('account.log_team_destroyed', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'employee_added_to_team':
                $sentence = trans('account.log_employee_added_to_team', [
                    'employee' => $log->object->{'employee_name'},
                    'team' => $log->object->{'team_name'},
                ]);
                break;

            case 'employee_removed_from_team':
                $sentence = trans('account.log_employee_removed_from_team', [
                    'employee' => $log->object->{'employee_name'},
                    'team' => $log->object->{'team_name'},
                ]);
                break;

            case 'employee_updated':
                $sentence = trans('account.log_employee_updated', [
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_updated_hiring_information':
                $sentence = trans('account.log_employee_updated_hiring_information', [
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'manager_assigned':
                $sentence = trans('account.log_manager_assigned', [
                    'name' => $log->object->{'manager_name'},
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'manager_unassigned':
                $sentence = trans('account.log_manager_unassigned', [
                    'name' => $log->object->{'manager_name'},
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_invited_to_become_user':
                $sentence = trans('account.log_employee_invited_to_become_user', [
                    'employee' => $log->object->{'employee_first_name'}.' '.$log->object->{'employee_last_name'},
                ]);
                break;

            case 'position_created':
                $sentence = trans('account.log_position_created', [
                    'name' => $log->object->{'position_title'},
                ]);
                break;

            case 'position_updated':
                $sentence = trans('account.log_position_updated', [
                    'former_name' => $log->object->{'position_old_title'},
                    'new_name' => $log->object->{'position_title'},
                ]);
                break;

            case 'position_destroyed':
                $sentence = trans('account.log_position_destroyed', [
                    'name' => $log->object->{'position_title'},
                ]);
                break;

            case 'position_assigned':
                $sentence = trans('account.log_position_assigned', [
                    'name' => $log->object->{'position_title'},
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'position_removed':
                $sentence = trans('account.log_position_removed', [
                    'name' => $log->object->{'position_title'},
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'flow_created':
                $sentence = trans('account.log_flow_created', [
                    'name' => $log->object->{'flow_name'},
                ]);
                break;

            case 'employee_worklog_logged':
                $sentence = trans('account.log_employee_worklog_logged');
                break;

            case 'employee_status_created':
                $sentence = trans('account.log_employee_status_created', [
                    'name' => $log->object->{'employee_status_name'},
                ]);
                break;

            case 'employee_status_updated':
                $sentence = trans('account.log_employee_status_updated', [
                    'former_name' => $log->object->{'employee_status_old_name'},
                    'new_name' => $log->object->{'employee_status_new_name'},
                ]);
                break;

            case 'employee_status_destroyed':
                $sentence = trans('account.log_employee_status_destroyed', [
                    'name' => $log->object->{'employee_status_name'},
                ]);
                break;

            case 'employee_status_assigned':
                $sentence = trans('account.log_employee_status_assigned', [
                    'name' => $log->object->{'employee_status_name'},
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_status_removed':
                $sentence = trans('account.log_employee_status_removed', [
                    'name' => $log->object->{'employee_status_name'},
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'company_news_created':
                $sentence = trans('account.log_company_news_created', [
                    'name' => $log->object->{'company_news_title'},
                ]);
                break;

            case 'company_news_updated':
                $sentence = trans('account.log_company_news_updated', [
                    'name' => $log->object->{'company_news_title'},
                ]);
                break;

            case 'company_news_destroyed':
                $sentence = trans('account.log_company_news_destroyed', [
                    'name' => $log->object->{'company_news_title'},
                ]);
                break;

            case 'employee_morale_logged':
                $sentence = trans('account.log_employee_morale_logged', [
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'company_pto_policy_created':
                $sentence = trans('account.log_company_pto_policy_created', [
                    'year' => $log->object->{'company_pto_policy_year'},
                ]);
                break;

            case 'company_pto_policy_updated':
                $sentence = trans('account.log_company_pto_policy_updated', [
                    'year' => $log->object->{'company_pto_policy_year'},
                ]);
                break;

            case 'time_off_created':
                $sentence = trans('account.log_company_time_off_created', [
                    'date' => $log->object->{'planned_holiday_date'},
                ]);
                break;

            case 'time_off_destroyed':
                $sentence = trans('account.log_company_time_off_destroyed', [
                    'date' => $log->object->{'planned_holiday_date'},
                ]);
                break;

            case 'address_added_to_employee':
                $sentence = trans('account.log_employee_address_set', [
                    'address' => $log->object->{'partial_address'},
                ]);
                break;

            case 'pronoun_assigned_to_employee':
                $sentence = trans('account.log_employee_pronoun_set', [
                    'name' => $log->object->{'pronoun_label'},
                    'employee' => $log->object->{'employee_name'},
                ]);
                break;

            case 'pronoun_removed_from_employee':
                $sentence = trans('account.log_employee_pronoun_removed', [
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_description_set':
                $sentence = trans('account.log_employee_description_set', [
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_description_cleared':
                $sentence = trans('account.log_employee_description_cleared', [
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_birthday_set':
                $sentence = trans('account.log_employee_birthday_set', [
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'team_description_set':
                $sentence = trans('account.log_team_description_set', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'team_description_cleared':
                $sentence = trans('account.log_team_description_cleared', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'team_useful_link_created':
                $sentence = trans('account.log_team_useful_link_created', [
                    'name' => $log->object->{'team_name'},
                    'link_name' => $log->object->{'link_name'},
                ]);
                break;

            case 'team_useful_link_updated':
                $sentence = trans('account.log_team_useful_link_updated', [
                    'name' => $log->object->{'team_name'},
                    'link_name' => $log->object->{'link_name'},
                ]);
                break;

            case 'team_useful_link_destroyed':
                $sentence = trans('account.log_team_useful_link_destroyed', [
                    'name' => $log->object->{'team_name'},
                    'link_name' => $log->object->{'link_name'},
                ]);
                break;

            case 'team_news_created':
                $sentence = trans('account.log_team_news_created', [
                    'name' => $log->object->{'team_name'},
                    'news_name' => $log->object->{'team_news_title'},
                ]);
                break;

            case 'team_news_updated':
                $sentence = trans('account.log_team_news_updated', [
                    'name' => $log->object->{'team_name'},
                    'news_name' => $log->object->{'team_news_title'},
                ]);
                break;

            case 'team_news_destroyed':
                $sentence = trans('account.log_team_news_destroyed', [
                    'name' => $log->object->{'team_name'},
                    'news_name' => $log->object->{'team_news_title'},
                ]);
                break;

            case 'employee_personal_details_set':
                $sentence = trans('account.log_employee_personal_details_set', [
                    'name' => $log->object->{'employee_name'},
                    'email' => $log->object->{'employee_email'},
                ]);
                break;

            case 'team_leader_assigned':
                $sentence = trans('account.log_team_leader_assigned', [
                    'name' => $log->object->{'team_leader_name'},
                    'team_name' => $log->object->{'team_name'},
                ]);
                break;

            case 'team_leader_removed':
                $sentence = trans('account.log_team_leader_removed', [
                    'name' => $log->object->{'team_leader_name'},
                    'team_name' => $log->object->{'team_name'},
                ]);
                break;

            case 'employee_work_from_home_logged':
                $sentence = trans('account.log_employee_work_from_home_logged', [
                    'name' => $log->object->{'employee_name'},
                    'date' => $log->object->{'date'},
                ]);
                break;

            case 'employee_work_from_home_destroyed':
                $sentence = trans('account.log_employee_work_from_home_destroyed', [
                    'name' => $log->object->{'employee_name'},
                    'date' => $log->object->{'date'},
                ]);
                break;

            case 'question_created':
                $sentence = trans('account.log_question_created', [
                    'title' => $log->object->{'question_title'},
                    'status' => $log->object->{'question_status'},
                ]);
                break;

            case 'question_updated':
                $sentence = trans('account.log_question_updated', [
                    'title' => $log->object->{'question_title'},
                    'old_title' => $log->object->{'question_old_title'},
                ]);
                break;

            case 'question_destroyed':
                $sentence = trans('account.log_question_destroyed', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'question_activated':
                $sentence = trans('account.log_question_activated', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'question_deactivated':
                $sentence = trans('account.log_question_deactivated', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'answer_created':
                $sentence = trans('account.log_answer_created', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'answer_updated':
                $sentence = trans('account.log_answer_updated', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'answer_destroyed':
                $sentence = trans('account.log_answer_destroyed', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'hardware_created':
                $sentence = trans('account.log_hardware_created', [
                    'name' => $log->object->{'hardware_name'},
                ]);
                break;

            case 'hardware_updated':
                $sentence = trans('account.log_hardware_updated', [
                    'name' => $log->object->{'hardware_name'},
                    'old_name' => $log->object->{'hardware_old_name'},
                ]);
                break;

            case 'hardware_destroyed':
                $sentence = trans('account.log_hardware_destroyed', [
                    'name' => $log->object->{'hardware_name'},
                ]);
                break;

            case 'hardware_lent':
                $sentence = trans('account.log_hardware_lent', [
                    'hardware_name' => $log->object->{'hardware_name'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'hardware_regained':
                $sentence = trans('account.log_hardware_regained', [
                    'hardware_name' => $log->object->{'hardware_name'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'recent_ship_created':
                $sentence = trans('account.log_recent_ship_created', [
                    'team_name' => $log->object->{'team_name'},
                    'ship_title' => $log->object->{'ship_title'},
                ]);
                break;

            case 'employee_attached_to_recent_ship':
                $sentence = trans('account.log_employee_attached_to_recent_ship', [
                    'employee_name' => $log->object->{'employee_name'},
                    'ship_title' => $log->object->{'ship_title'},
                    'team_name' => $log->object->{'team_name'},
                ]);
                break;

            case 'ship_destroyed':
                $sentence = trans('account.log_recent_ship_destroyed', [
                    'ship_title' => $log->object->{'ship_title'},
                    'team_name' => $log->object->{'team_name'},
                ]);
                break;

            case 'skill_created':
                $sentence = trans('account.log_skill_created', [
                    'skill_id' => $log->object->{'skill_id'},
                    'skill_name' => $log->object->{'skill_name'},
                ]);
                break;

            case 'skill_associated_with_employee':
                $sentence = trans('account.log_skill_associated_with_employee', [
                    'skill_id' => $log->object->{'skill_id'},
                    'skill_name' => $log->object->{'skill_name'},
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'skill_removed_from_an_employee':
                $sentence = trans('account.log_skill_removed_from_an_employee', [
                    'skill_id' => $log->object->{'skill_id'},
                    'skill_name' => $log->object->{'skill_name'},
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'skill_destroyed':
                $sentence = trans('account.log_skill_destroyed', [
                    'name' => $log->object->{'skill_name'},
                ]);
                break;

            case 'skill_updated':
                $sentence = trans('account.log_skill_updated', [
                    'skill_id' => $log->object->{'skill_id'},
                    'skill_old_name' => $log->object->{'skill_old_name'},
                    'skill_new_name' => $log->object->{'skill_new_name'},
                ]);
                break;

            case 'expense_category_created':
                $sentence = trans('account.log_expense_category_created', [
                    'id' => $log->object->{'expense_category_id'},
                    'name' => $log->object->{'expense_category_name'},
                ]);
                break;

            case 'expense_category_updated':
                $sentence = trans('account.log_expense_category_updated', [
                    'id' => $log->object->{'expense_category_id'},
                    'old_name' => $log->object->{'expense_category_old_name'},
                    'new_name' => $log->object->{'expense_category_new_name'},
                ]);
                break;

            case 'expense_category_destroyed':
                $sentence = trans('account.log_expense_category_destroyed', [
                    'name' => $log->object->{'expense_category_name'},
                ]);
                break;

            case 'task_created':
                $sentence = trans('account.log_task_created', [
                    'title' => $log->object->{'title'},
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'company_renamed':
                $sentence = trans('account.log_company_renamed', [
                    'old_name' => $log->object->{'old_name'},
                    'new_name' => $log->object->{'new_name'},
                ]);
                break;

            case 'company_currency_updated':
                $sentence = trans('account.log_company_currency_updated', [
                    'old_currency' => $log->object->{'old_currency'},
                    'new_currency' => $log->object->{'new_currency'},
                ]);
                break;

            case 'expense_created':
                $sentence = trans('account.log_expense_created', [
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_accepted_by_manager':
                $sentence = trans('account.log_expense_accepted_by_manager', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_accepted_by_accounting':
                $sentence = trans('account.log_expense_accepted_by_accounting', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_rejected_by_accounting':
                $sentence = trans('account.log_expense_rejected_by_accounting', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'employee_allowed_to_manage_expenses':
                $sentence = trans('account.log_employee_allowed_to_manage_expenses', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_disallowed_to_manage_expenses':
                $sentence = trans('account.log_employee_disallowed_to_manage_expenses', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'rate_your_manager_survey_answered':
                $sentence = trans('account.log_rate_your_manager_survey_answered', [
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'employee_twitter_set':
                $sentence = trans('account.log_employee_twitter_set', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'twitter' => $log->object->{'twitter'},
                ]);
                break;

            case 'employee_twitter_reset':
                $sentence = trans('account.log_employee_twitter_reset', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_slack_set':
                $sentence = trans('account.log_employee_slack_set', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'slack' => $log->object->{'slack'},
                ]);
                break;

            case 'employee_slack_reset':
                $sentence = trans('account.log_employee_slack_reset', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'employee_hiring_date_set':
                $sentence = trans('account.log_employee_hiring_date_set', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'hiring_date' => $log->object->{'hiring_date'},
                ]);
                break;

            case 'one_on_one_entry_created':
                $sentence = trans('account.log_one_on_one_entry_created', [
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'one_on_one_entry_destroyed':
                $sentence = trans('account.log_one_on_one_entry_destroyed', [
                    'happened_at' => $log->object->{'happened_at'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'one_on_one_talking_point_created':
                $sentence = trans('account.log_one_on_one_talking_point_created', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'one_on_one_talking_point_id' => $log->object->{'one_on_one_talking_point_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'one_on_one_action_item_created':
                $sentence = trans('account.log_one_on_one_action_item_created', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'one_on_one_action_item_id' => $log->object->{'one_on_one_action_item_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'one_on_one_note_created':
                $sentence = trans('account.log_one_on_one_note_created', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'one_on_one_note_id' => $log->object->{'one_on_one_note_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'one_on_one_action_item_destroyed':
                $sentence = trans('account.log_one_on_one_action_item_destroyed', [
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'happened_at' => $log->object->{'happened_at'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'one_on_one_talking_point_destroyed':
                $sentence = trans('account.log_one_on_one_talking_point_destroyed', [
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'happened_at' => $log->object->{'happened_at'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'one_on_one_note_destroyed':
                $sentence = trans('account.log_one_on_one_note_destroyed', [
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'happened_at' => $log->object->{'happened_at'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'one_on_one_note_marked_happened':
                $sentence = trans('account.log_one_on_one_note_marked_happened', [
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'happened_at' => $log->object->{'happened_at'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;
        }

        return $sentence;
    }

    /**
     * Transform an action into an understandable sentence.
     *
     * @param EmployeeLog $log
     *
     * @return string
     */
    public static function processEmployeeLog(EmployeeLog $log): string
    {
        $sentence = '';

        switch ($log->action) {
            case 'employee_created':
                $sentence = trans('account.employee_log_employee_created');
                break;

            case 'employee_locked':
                $sentence = trans('account.employee_log_employee_locked');
                break;

            case 'employee_unlocked':
                $sentence = trans('account.employee_log_employee_unlocked');
                break;

            case 'manager_assigned':
                $sentence = trans('account.employee_log_manager_assigned', [
                    'name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'direct_report_assigned':
                $sentence = trans('account.employee_log_direct_report_assigned', [
                    'name' => $log->object->{'direct_report_name'},
                ]);
                break;

            case 'manager_unassigned':
                $sentence = trans('account.employee_log_manager_unassigned', [
                    'name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'direct_report_unassigned':
                $sentence = trans('account.employee_log_direct_report_unassigned', [
                    'name' => $log->object->{'direct_report_name'},
                ]);
                break;

            case 'position_assigned':
                $sentence = trans('account.employee_log_position_assigned', [
                    'name' => $log->object->{'position_title'},
                ]);
                break;

            case 'position_removed':
                $sentence = trans('account.employee_log_position_removed', [
                    'name' => $log->object->{'position_title'},
                ]);
                break;

            case 'employee_added_to_team':
                $sentence = trans('account.employee_log_employee_added_to_team', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'employee_removed_from_team':
                $sentence = trans('account.employee_log_employee_removed_from_team', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'employee_worklog_logged':
                $sentence = trans('account.employee_log_employee_worklog_logged');
                break;

            case 'employee_status_assigned':
                $sentence = trans('account.employee_log_employee_status_assigned', [
                    'name' => $log->object->{'employee_status_name'},
                ]);
                break;

            case 'employee_status_removed':
                $sentence = trans('account.employee_log_employee_status_removed', [
                    'name' => $log->object->{'employee_status_name'},
                ]);
                break;

            case 'morale_logged':
                $sentence = trans('account.employee_log_morale_logged', [
                    'name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'time_off_created':
                $sentence = trans('account.employee_log_time_off_created', [
                    'date' => $log->object->{'planned_holiday_date'},
                ]);
                break;

            case 'time_off_destroyed':
                $sentence = trans('account.employee_log_time_off_destroyed', [
                    'date' => $log->object->{'planned_holiday_date'},
                ]);
                break;

            case 'address_added':
                $sentence = trans('account.employee_log_address_set', [
                    'address' => $log->object->{'partial_address'},
                ]);
                break;

            case 'pronoun_assigned':
                $sentence = trans('account.employee_log_pronoun_set', [
                    'name' => $log->object->{'pronoun_label'},
                ]);
                break;

            case 'pronoun_removed':
                $sentence = trans('account.employee_log_pronoun_removed');
                break;

            case 'description_set':
                $sentence = trans('account.employee_log_description_set');
                break;

            case 'description_cleared':
                $sentence = trans('account.employee_log_description_cleared');
                break;

            case 'birthday_set':
                $sentence = trans('account.employee_birthday_set');
                break;

            case 'personal_details_set':
                $sentence = trans('account.employee_personal_details_set', [
                    'name' => $log->object->{'name'},
                    'email' => $log->object->{'email'},
                ]);
                break;

            case 'work_from_home_logged':
                $sentence = trans('account.employee_log_work_from_home_logged', [
                    'date' => $log->object->{'date'},
                ]);
                break;

            case 'work_from_home_destroyed':
                $sentence = trans('account.employee_log_work_from_home_destroyed', [
                    'date' => $log->object->{'date'},
                ]);
                break;

            case 'answer_created':
                $sentence = trans('account.employee_log_answer_created', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'answer_updated':
                $sentence = trans('account.employee_log_answer_updated', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'answer_destroyed':
                $sentence = trans('account.employee_log_answer_destroyed', [
                    'title' => $log->object->{'question_title'},
                ]);
                break;

            case 'employee_attached_to_recent_ship':
                $sentence = trans('account.employee_log_employee_attached_to_recent_ship', [
                    'ship_title' => $log->object->{'ship_title'},
                    'team_name' => $log->object->{'team_name'},
                ]);
                break;

            case 'skill_associated_with_employee':
                $sentence = trans('account.employee_log_skill_associated_with_employee', [
                    'name' => $log->object->{'skill_name'},
                ]);
                break;

            case 'skill_removed_from_an_employee':
                $sentence = trans('account.employee_log_skill_removed_from_an_employee', [
                    'name' => $log->object->{'skill_name'},
                ]);
                break;

            case 'task_created':
                $sentence = trans('account.employee_log_task_created', [
                    'title' => $log->object->{'title'},
                ]);
                break;

            case 'expense_created':
                $sentence = trans('account.employee_log_expense_created', [
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_accepted_for_employee':
                $sentence = trans('account.employee_log_expense_accepted_for_employee', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_accepted_by_manager':
                $sentence = trans('account.employee_log_expense_accepted_by_manager', [
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_rejected_by_manager':
                $sentence = trans('account.employee_log_expense_rejected_by_manager', [
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_rejected_for_employee':
                $sentence = trans('account.employee_log_expense_rejected_for_employee', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_accepted_by_accounting_for_employee':
                $sentence = trans('account.employee_log_expense_accepted_by_accounting_for_employee', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_accepted_by_accounting':
                $sentence = trans('account.employee_log_expense_accepted_by_accounting', [
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_rejected_by_accounting_for_employee':
                $sentence = trans('account.employee_log_expense_rejected_by_accounting_for_employee', [
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'expense_rejected_by_accounting':
                $sentence = trans('account.employee_log_expense_rejected_by_accounting', [
                    'expense_id' => $log->object->{'expense_id'},
                    'expense_title' => $log->object->{'expense_title'},
                    'expense_amount' => $log->object->{'expense_amount'},
                    'expensed_at' => $log->object->{'expensed_at'},
                ]);
                break;

            case 'employee_allowed_to_manage_expenses':
                $sentence = trans('account.employee_log_employee_allowed_to_manage_expenses');
                break;

            case 'employee_disallowed_to_manage_expenses':
                $sentence = trans('account.employee_log_employee_disallowed_to_manage_expenses');
                break;

            case 'rate_your_manager_survey_answered':
                $sentence = trans('account.log_rate_your_manager_survey_answered', [
                    'manager_id' => $log->object->{'manager_id'},
                    'manager_name' => $log->object->{'manager_name'},
                ]);
                break;

            case 'twitter_set':
                $sentence = trans('account.employee_log_employee_twitter_set', [
                    'twitter' => $log->object->{'twitter'},
                ]);
                break;

            case 'twitter_reset':
                $sentence = trans('account.employee_log_employee_twitter_reset');
                break;

            case 'slack_set':
                $sentence = trans('account.employee_log_employee_slack_set', [
                    'slack' => $log->object->{'slack'},
                ]);
                break;

            case 'slack_reset':
                $sentence = trans('account.employee_log_employee_slack_reset');
                break;

            case 'hiring_date_set':
                $sentence = trans('account.employee_log_hiring_date_set', [
                    'hiring_date' => $log->object->{'hiring_date'},
                ]);
                break;

            case 'one_on_one_entry_created':
                $sentence = trans('account.employee_log_one_on_one_entry_created', [
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'one_on_one_entry_destroyed':
                $sentence = trans('account.employee_log_one_on_one_entry_destroyed', [
                    'happened_at' => $log->object->{'happened_at'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'one_on_one_talking_point_created':
                $sentence = trans('account.employee_log_one_on_one_talking_point_created', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'one_on_one_talking_point_id' => $log->object->{'one_on_one_talking_point_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'one_on_one_action_item_created':
                $sentence = trans('account.employee_log_one_on_one_action_item_created', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'one_on_one_action_item_id' => $log->object->{'one_on_one_action_item_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'one_on_one_note_created':
                $sentence = trans('account.employee_log_one_on_one_note_created', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'one_on_one_note_id' => $log->object->{'one_on_one_note_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'one_on_one_talking_point_destroyed':
                $sentence = trans('account.employee_log_one_on_one_talking_point_destroyed', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'one_on_one_action_item_destroyed':
                $sentence = trans('account.employee_log_one_on_one_action_item_destroyed', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'one_on_one_note_destroyed':
                $sentence = trans('account.employee_log_one_on_one_note_destroyed', [
                    'happened_at' => $log->object->{'happened_at'},
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;

            case 'one_on_one_note_marked_happened':
                $sentence = trans('account.employee_log_one_on_one_note_marked_happened', [
                    'one_on_one_entry_id' => $log->object->{'one_on_one_entry_id'},
                    'happened_at' => $log->object->{'happened_at'},
                    'employee_id' => $log->object->{'employee_id'},
                    'employee_name' => $log->object->{'employee_name'},
                ]);
                break;
        }

        return $sentence;
    }

    /**
     * Transform an action into an understandable sentence.
     *
     * @param TeamLog $log
     *
     * @return string
     */
    public static function processTeamLog(TeamLog $log): string
    {
        $sentence = '';

        switch ($log->action) {
            case 'team_created':
                $sentence = trans('account.team_log_team_created', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'team_updated':
                $sentence = trans('account.team_log_team_updated', [
                    'old_name' => $log->object->{'team_old_name'},
                    'new_name' => $log->object->{'team_new_name'},
                ]);
                break;

            case 'employee_added_to_team':
                $sentence = trans('account.team_log_employee_added_to_team', [
                    'employee_name' => $log->object->{'employee_name'},
                    'team_name' => $log->object->{'team_name'},
                ]);
                break;

            case 'employee_removed_from_team':
                $sentence = trans('account.team_log_employee_removed_from_team', [
                    'employee_name' => $log->object->{'employee_name'},
                    'team_name' => $log->object->{'team_name'},
                ]);
                break;

            case 'team_leader_assigned':
                $sentence = trans('account.team_log_team_leader_assigned', [
                    'name' => $log->object->{'team_leader_name'},
                ]);
                break;

            case 'team_leader_removed':
                $sentence = trans('account.team_log_team_leader_removed', [
                    'name' => $log->object->{'team_leader_name'},
                ]);
                break;

            case 'description_set':
                $sentence = trans('account.team_log_description_set', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'description_cleared':
                $sentence = trans('account.team_log_description_cleared', [
                    'name' => $log->object->{'team_name'},
                ]);
                break;

            case 'useful_link_created':
                $sentence = trans('account.team_log_useful_link_created', [
                    'name' => $log->object->{'link_name'},
                ]);
                break;

            case 'useful_link_updated':
                $sentence = trans('account.team_log_useful_link_updated', [
                    'name' => $log->object->{'link_name'},
                ]);
                break;

            case 'useful_link_destroyed':
                $sentence = trans('account.team_log_useful_link_destroyed', [
                    'name' => $log->object->{'link_name'},
                ]);
                break;

            case 'team_news_created':
                $sentence = trans('account.team_log_team_news_created', [
                    'name' => $log->object->{'team_news_title'},
                ]);
                break;

            case 'team_news_updated':
                $sentence = trans('account.team_log_team_news_updated', [
                    'title' => $log->object->{'team_news_title'},
                    'old_title' => $log->object->{'team_news_old_title'},
                ]);
                break;

            case 'team_news_destroyed':
                $sentence = trans('account.team_log_team_news_destroyed', [
                    'title' => $log->object->{'team_news_title'},
                ]);
                break;

            case 'recent_ship_created':
                $sentence = trans('account.team_log_recent_ship_created', [
                    'title' => $log->object->{'ship_title'},
                ]);
                break;

            case 'ship_destroyed':
                $sentence = trans('account.team_log_ship_destroyed', [
                    'title' => $log->object->{'ship_title'},
                ]);
                break;
        }

        return $sentence;
    }
}
