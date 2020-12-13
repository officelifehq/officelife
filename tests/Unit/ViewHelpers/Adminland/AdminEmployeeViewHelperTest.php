<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminEmployeeViewHelper;

class AdminEmployeeViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_statistics_about_employees(): void
    {
        $michael = $this->createAdministrator();
        factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'hired_at' => Carbon::now(),
            'locked' => true,
        ]);
        factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'hired_at' => null,
            'locked' => false,
        ]);

        $array = AdminEmployeeViewHelper::index($michael->company->employees, $michael->company);

        $this->assertEquals(
            [
                'number_of_locked_accounts' => 1,
                'number_of_active_accounts' => 2,
                'number_of_employees' => 3,
                'number_of_employees_without_hire_date' => 2,
                'url_all' => env('APP_URL').'/'.$michael->company_id.'/account/employees/all',
                'url_active' => env('APP_URL').'/'.$michael->company_id.'/account/employees/active',
                'url_locked' => env('APP_URL').'/'.$michael->company_id.'/account/employees/locked',
                'url_no_hiring_date' => env('APP_URL').'/'.$michael->company_id.'/account/employees/noHiringDate',
                'url_permission' => env('APP_URL').'/'.$michael->company_id.'/account/employees/permission',
                'url_new' => env('APP_URL').'/'.$michael->company_id.'/account/employees/create',
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_information_about_all_employees_in_the_company(): void
    {
        $michael = $this->createAdministrator();

        $collection = AdminEmployeeViewHelper::all($michael->company->employees, $michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'permission_level' => $michael->permission_level,
                    'avatar' => $michael->avatar,
                    'invitation_link' => $michael->invitation_link,
                    'invited' => (! $michael->invitation_used_at && $michael->invitation_link) === true,
                    'lock_status' => $michael->locked,
                    'url_view' => route('employees.show', [
                        'company' => $michael->company,
                        'employee' => $michael,
                    ]),
                    'url_delete' => route('account.delete', [
                        'company' => $michael->company,
                        'employee' => $michael,
                    ]),
                    'url_lock' => route('account.lock', [
                        'company' => $michael->company,
                        'employee' => $michael,
                    ]),
                    'url_unlock' => route('account.unlock', [
                        'company' => $michael->company,
                        'employee' => $michael,
                    ]),
                ],
            ],
            $collection->toArray()
        );
    }
}
