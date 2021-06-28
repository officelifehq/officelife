<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Helpers\PermissionHelper;
use App\Models\Company\EmployeeStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PermissionHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_lets_the_employee_see_the_full_birthdate(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_full_birthdate']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_full_birthdate']);
    }

    /** @test */
    public function it_lets_the_employee_see_the_expenses(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $manager = $this->createEmployee();
        $employee = $this->createEmployee();
        $accountant = Employee::factory()->create([
            'can_manage_expenses' => true,
        ]);
        $directReport = $this->createDirectReport($manager);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertFalse($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertFalse($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertFalse($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($accountant, $administrator);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($accountant, $hr);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($accountant, $employee);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertTrue($permission['can_see_expenses']);

        $permission = PermissionHelper::permissions($manager, $employee);
        $this->assertFalse($permission['can_see_expenses']);
    }

    /** @test */
    public function it_lets_the_employee_see_the_work_from_home_history(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_work_from_home_history']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_work_from_home_history']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_work_from_home_history']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_work_from_home_history']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_work_from_home_history']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_work_from_home_history']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_work_from_home_history']);
    }

    /** @test */
    public function it_lets_the_employee_see_the_work_log_history(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_work_log_history']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_work_log_history']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_work_log_history']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_work_log_history']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_work_log_history']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_work_log_history']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_work_log_history']);
    }

    /** @test */
    public function it_lets_the_employee_manage_hierarchy(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_manage_hierarchy']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_manage_hierarchy']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_manage_hierarchy']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_manage_hierarchy']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_manage_hierarchy']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_manage_hierarchy']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_manage_hierarchy']);
    }

    /** @test */
    public function it_lets_the_employee_manage_position(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_manage_position']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_manage_position']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_manage_position']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_manage_position']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_manage_position']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_manage_position']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_manage_position']);
    }

    /** @test */
    public function it_lets_the_employee_manage_team(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_manage_teams']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_manage_teams']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_manage_teams']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_manage_teams']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_manage_teams']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_manage_teams']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_manage_teams']);
    }

    /** @test */
    public function it_lets_the_employee_manage_pronouns(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_manage_pronouns']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_manage_pronouns']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_manage_pronouns']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_manage_pronouns']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_manage_pronouns']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_manage_pronouns']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_manage_pronouns']);
    }

    /** @test */
    public function it_lets_the_employee_manage_status(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_manage_status']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_manage_status']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_manage_status']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_manage_status']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_manage_status']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_manage_status']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_manage_status']);
    }

    /** @test */
    public function it_lets_the_employee_manage_skills(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_manage_skills']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_manage_skills']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_manage_skills']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_manage_skills']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_manage_skills']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_manage_skills']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_manage_skills']);
    }

    /** @test */
    public function it_lets_the_employee_manage_description(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_manage_description']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_manage_description']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_manage_description']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_manage_description']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_manage_description']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_manage_description']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_manage_description']);
    }

    /** @test */
    public function it_lets_the_employee_edit_profile(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_edit_profile']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_edit_profile']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_edit_profile']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_edit_profile']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_edit_profile']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_edit_profile']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_edit_profile']);
    }

    /** @test */
    public function it_lets_the_employee_delete_profile(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertFalse($permission['can_delete_profile']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_delete_profile']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_delete_profile']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertFalse($permission['can_delete_profile']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_delete_profile']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_delete_profile']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_delete_profile']);
    }

    /** @test */
    public function it_lets_the_employee_see_audit_log(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_audit_log']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_audit_log']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_audit_log']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_audit_log']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_audit_log']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_see_audit_log']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_audit_log']);
    }

    /** @test */
    public function it_lets_the_employee_see_complete_address(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_complete_address']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_complete_address']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_complete_address']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_complete_address']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_complete_address']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_complete_address']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_complete_address']);
    }

    /** @test */
    public function it_lets_the_employee_see_one_on_one_with_manager(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $manager = $this->createEmployee();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();
        $directReport = $this->createDirectReport($manager);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_one_on_one_with_manager']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_one_on_one_with_manager']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_one_on_one_with_manager']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_one_on_one_with_manager']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_one_on_one_with_manager']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_one_on_one_with_manager']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertTrue($permission['can_see_one_on_one_with_manager']);

        $permission = PermissionHelper::permissions($manager, $employee);
        $this->assertFalse($permission['can_see_one_on_one_with_manager']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_one_on_one_with_manager']);
    }

    /** @test */
    public function it_lets_the_employee_see_performance_tab(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $manager = $this->createEmployee();
        $employee = $this->createEmployee();
        $directReport = $this->createDirectReport($manager);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_performance_tab']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_performance_tab']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_performance_tab']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_performance_tab']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_performance_tab']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_performance_tab']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertTrue($permission['can_see_performance_tab']);

        $permission = PermissionHelper::permissions($manager, $employee);
        $this->assertFalse($permission['can_see_performance_tab']);
    }

    /** @test */
    public function it_lets_the_employee_see_administration_tab(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $manager = $this->createEmployee();
        $employee = $this->createEmployee();
        $directReport = $this->createDirectReport($manager);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_administration_tab']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_administration_tab']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_administration_tab']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_administration_tab']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_administration_tab']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_administration_tab']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertTrue($permission['can_see_administration_tab']);

        $permission = PermissionHelper::permissions($manager, $employee);
        $this->assertFalse($permission['can_see_administration_tab']);
    }

    /** @test */
    public function it_lets_the_employee_see_hardware(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_hardware']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_hardware']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_hardware']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_hardware']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_hardware']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_hardware']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_hardware']);
    }

    /** @test */
    public function it_lets_the_employee_see_software(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_software']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_software']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_software']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_software']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_software']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_software']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_software']);
    }

    /** @test */
    public function it_lets_the_employee_see_contract_renewal_date(): void
    {
        $administrator = Employee::factory()->asAdministrator()->create();
        $hr = $this->createHR();
        $manager = $this->createEmployee();
        $employee = $this->createEmployee();
        $directReport = $this->createDirectReport($manager);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertFalse($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertFalse($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertFalse($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertFalse($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertFalse($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertFalse($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($manager, $employee);
        $this->assertFalse($permission['can_see_contract_renewal_date']);

        $employee = $this->setEmployeeStatus($employee, EmployeeStatus::EXTERNAL);
        $directReport = $this->setEmployeeStatus($directReport, EmployeeStatus::EXTERNAL);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_contract_renewal_date']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertTrue($permission['can_see_contract_renewal_date']);
    }

    /** @test */
    public function it_lets_the_employee_see_timesheets(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $manager = $this->createEmployee();
        $employee = $this->createEmployee();
        $directReport = $this->createDirectReport($manager);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_see_timesheets']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_see_timesheets']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_timesheets']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_see_timesheets']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_timesheets']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_see_timesheets']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertTrue($permission['can_see_timesheets']);

        $permission = PermissionHelper::permissions($manager, $employee);
        $this->assertFalse($permission['can_see_timesheets']);
    }

    /** @test */
    public function it_lets_the_employee_update_avatar(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_update_avatar']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_update_avatar']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_update_avatar']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_update_avatar']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_update_avatar']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_update_avatar']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_update_avatar']);
    }

    /** @test */
    public function it_lets_the_employee_edit_hired_at(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_edit_hired_at_information']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_edit_hired_at_information']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_edit_hired_at_information']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_edit_hired_at_information']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_edit_hired_at_information']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_edit_hired_at_information']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_edit_hired_at_information']);
    }

    /** @test */
    public function it_lets_the_employee_edit_contract_information(): void
    {
        $administrator = Employee::factory()->asAdministrator()->create();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_edit_contract_information']);

        $employee = $this->setEmployeeStatus($employee, EmployeeStatus::EXTERNAL);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_edit_contract_information']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_edit_contract_information']);
    }

    /** @test */
    public function it_lets_the_employee_see_the_edit_contract_information_tab(): void
    {
        $administrator = Employee::factory()->asAdministrator()->create();
        $hr = $this->createHR();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $employee = $this->setEmployeeStatus($employee, EmployeeStatus::EXTERNAL);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_see_edit_contract_information_tab']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertFalse($permission['can_see_edit_contract_information_tab']);
    }

    /** @test */
    public function it_lets_the_employee_delete_worklog(): void
    {
        $administrator = $this->createAdministrator();
        $hr = $this->createHR();
        $manager = $this->createEmployee();
        $employee = $this->createEmployee();
        $anotherEmployee = $this->createEmployee();
        $directReport = $this->createDirectReport($manager);

        $permission = PermissionHelper::permissions($administrator, $administrator);
        $this->assertTrue($permission['can_delete_worklog']);

        $permission = PermissionHelper::permissions($administrator, $hr);
        $this->assertTrue($permission['can_delete_worklog']);

        $permission = PermissionHelper::permissions($administrator, $employee);
        $this->assertTrue($permission['can_delete_worklog']);

        $permission = PermissionHelper::permissions($hr, $hr);
        $this->assertTrue($permission['can_delete_worklog']);

        $permission = PermissionHelper::permissions($hr, $employee);
        $this->assertTrue($permission['can_delete_worklog']);

        $permission = PermissionHelper::permissions($employee, $employee);
        $this->assertTrue($permission['can_delete_worklog']);

        $permission = PermissionHelper::permissions($manager, $directReport);
        $this->assertTrue($permission['can_delete_worklog']);

        $permission = PermissionHelper::permissions($manager, $employee);
        $this->assertFalse($permission['can_delete_worklog']);

        $permission = PermissionHelper::permissions($employee, $anotherEmployee);
        $this->assertFalse($permission['can_delete_worklog']);
    }
}
