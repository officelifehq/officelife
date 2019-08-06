<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\ProvisionDefaultAccountData;

class ProvisionDefaultAccountDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_populates_default_data_in_the_account() : void
    {
        $adminEmployee = $this->createAdministrator();
        $user = factory(User::class)->create([]);

        $request = [
            'company_id' => $adminEmployee->company_id,
            'author_id' => $adminEmployee->user->id,
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
                'company_id' => $adminEmployee->company_id,
                'title' => $position,
            ]);
        }

        $statuses = [
            trans('app.default_employee_status_full_time'),
            trans('app.default_employee_status_part_time'),
        ];

        foreach ($statuses as $status) {
            $this->assertDatabaseHas('employee_statuses', [
                'company_id' => $adminEmployee->company_id,
                'name' => $status,
            ]);
        }
    }
}
