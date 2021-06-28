<?php

namespace App\Helpers;

use App\Models\Company\Employee;
use App\Models\Company\EmployeeStatus;

class PermissionHelper
{
    /**
     * Get all the permissions about what the logged employee can do
     * against another employee.
     *
     * @param Employee $loggedEmployee
     * @param Employee $employee
     * @return array
     */
    public static function permissions(Employee $loggedEmployee, Employee $employee): array
    {
        $loggedEmployeeIsManager = $loggedEmployee->isManagerOf($employee->id);

        // can see the complete birthdate of the given employee
        $canSeeFullBirthdate = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeFullBirthdate = true;
        }

        // can see expenses of the given employee
        $canSeeExpenses = $loggedEmployee->can_manage_expenses;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeExpenses = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeeExpenses = true;
        }

        // can see the work from home history of the given employee
        $canSeeWorkFromHomeHistory = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeWorkFromHomeHistory = true;
        }

        // can see the work log history of the given employee
        $canSeeWorkLogHistory = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeWorkLogHistory = true;
        }

        // can manage hierarchy of the given employee
        $canManageHierarchy = $loggedEmployee->permission_level <= 200;

        // can manage position of the given employee
        $canManagePosition = $loggedEmployee->permission_level <= 200;

        // can manage teams of the given employee
        $canManageTeam = $loggedEmployee->permission_level <= 200;

        // can manage pronouns of the given employee
        $canManagePronouns = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canManagePronouns = true;
        }

        // can manage status of the given employee
        $canManageStatus = $loggedEmployee->permission_level <= 200;

        // can manage skills of the given employee
        $canManageSkills = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canManageSkills = true;
        }

        // can manage description of the given employee
        $canManageDescription = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canManageDescription = true;
        }

        // can edit profile of the given employee
        $canEditProfile = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canEditProfile = true;
        }

        // can delete profile of the given employee
        $canDeleteProfile = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canDeleteProfile = false;
        }

        // can see audit log of the given employee
        $canSeeAuditLog = $loggedEmployee->permission_level <= 200;

        // can see complete address of the given employee
        $canSeeCompleteAddress = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeCompleteAddress = true;
        }

        // can see one on one with manager of the given employee
        $canSeeOneOnOneWithManager = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeOneOnOneWithManager = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeeOneOnOneWithManager = true;
        }

        // can see performance tab of the given employee
        $canSeePerformanceTab = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeePerformanceTab = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeePerformanceTab = true;
        }

        // can see administration tab of the given employee
        $canSeeAdministrationTab = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeAdministrationTab = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeeAdministrationTab = true;
        }

        // can see hardware of the given employee
        $canSeeHardware = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeHardware = true;
        }

        // can see softwares of the given employee
        $canSeeSoftware = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeSoftware = true;
        }

        // can see contract renewal date of the given employee
        $canSeeContractRenewalDate = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeContractRenewalDate = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeeContractRenewalDate = true;
        }
        if ($employee->status) {
            if ($employee->status->type == EmployeeStatus::INTERNAL) {
                $canSeeContractRenewalDate = false;
            }
        } else {
            $canSeeContractRenewalDate = false;
        }

        // can see timesheets of the given employee
        $canSeeTimesheets = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canSeeTimesheets = true;
        }
        if ($loggedEmployeeIsManager) {
            $canSeeTimesheets = true;
        }

        // can update avatar of the given employee
        $canUpdateAvatar = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canUpdateAvatar = true;
        }

        // can edit hired at information of the given employee
        $canEditHiredAt = $loggedEmployee->permission_level <= 200;

        // can edit contract information of the given employee
        if ($employee->status) {
            $canEditContractInfoTab = $employee->status->type == EmployeeStatus::EXTERNAL;
        } else {
            $canEditContractInfoTab = false;
        }
        $canEditContractInfoTab = $loggedEmployee->permission_level <= 200;

        // can see the Edit contract info tab when editing the employee
        $canSeeEditContractInformationTab = false;
        if ($employee->status) {
            if ($employee->status->type == EmployeeStatus::EXTERNAL && $canEditContractInfoTab) {
                $canSeeEditContractInformationTab = true;
            }
        }

        // can delete work log
        $canDeleteWorkLog = $loggedEmployee->permission_level <= 200;
        if ($loggedEmployee->id == $employee->id) {
            $canDeleteWorkLog = true;
        }
        if ($loggedEmployeeIsManager) {
            $canDeleteWorkLog = true;
        }

        return [
            'can_see_full_birthdate' => $canSeeFullBirthdate,
            'can_see_expenses' => $canSeeExpenses,
            'can_manage_hierarchy' => $canManageHierarchy,
            'can_manage_position' => $canManagePosition,
            'can_manage_pronouns' => $canManagePronouns,
            'can_manage_status' => $canManageStatus,
            'can_manage_teams' => $canManageTeam,
            'can_manage_skills' => $canManageSkills,
            'can_manage_description' => $canManageDescription,
            'can_see_work_from_home_history' => $canSeeWorkFromHomeHistory,
            'can_see_work_log_history' => $canSeeWorkLogHistory,
            'can_see_hardware' => $canSeeHardware,
            'can_see_software' => $canSeeSoftware,
            'can_edit_profile' => $canEditProfile,
            'can_delete_profile' => $canDeleteProfile,
            'can_see_audit_log' => $canSeeAuditLog,
            'can_see_complete_address' => $canSeeCompleteAddress,
            'can_see_performance_tab' => $canSeePerformanceTab,
            'can_see_administration_tab' => $canSeeAdministrationTab,
            'can_see_one_on_one_with_manager' => $canSeeOneOnOneWithManager,
            'can_see_contract_renewal_date' => $canSeeContractRenewalDate,
            'can_see_timesheets' => $canSeeTimesheets,
            'can_update_avatar' => $canUpdateAvatar,
            'can_edit_hired_at_information' => $canEditHiredAt,
            'can_see_edit_contract_information_tab' => $canSeeEditContractInformationTab,
            'can_edit_contract_information' => $canEditContractInfoTab,
            'can_delete_worklog' => $canDeleteWorkLog,
        ];
    }
}
