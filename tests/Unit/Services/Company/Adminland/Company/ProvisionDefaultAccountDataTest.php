<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\ProvisionDefaultAccountData;

class ProvisionDefaultAccountDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_populates_default_data_in_the_account() : void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        (new ProvisionDefaultAccountData)->execute($request);

        $positions = [
            trans('app.default_position_ceo'),
            trans('app.default_position_sales_representative'),
            trans('app.default_position_marketing_specialist'),
            trans('app.default_position_front_end_developer'),
        ];

        foreach ($positions as $position) {
            $this->assertDatabaseHas('positions', [
                'company_id' => $michael->company_id,
                'title' => $position,
            ]);
        }

        $statuses = [
            trans('app.default_employee_status_full_time'),
            trans('app.default_employee_status_part_time'),
        ];

        foreach ($statuses as $status) {
            $this->assertDatabaseHas('employee_statuses', [
                'company_id' => $michael->company_id,
                'name' => $status,
            ]);
        }

        $currentYear = Carbon::now();
        for ($i = 1; $i <= 15; $i++) {
            $this->assertDatabaseHas('company_pto_policies', [
                'company_id' => $michael->company_id,
                'year' => $currentYear->addYear()->format('Y'),
                'total_worked_days' => 261,
                'default_amount_of_allowed_holidays' => 30,
                'default_amount_of_sick_days' => 5,
                'default_amount_of_pto_days' => 5,
            ]);
        }
    }
}
