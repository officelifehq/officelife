<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Jobs\UpdateDashboardPreference;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateDashboardPreferenceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_the_user_preference_for_the_dashboard(): void
    {
        $michael = $this->createAdministrator();

        $request = [
            'employee_id' => $michael->id,
            'company_id' => $michael->company_id,
            'view' => 'company',
        ];

        UpdateDashboardPreference::dispatch($request);

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'default_dashboard_view' => 'company',
        ]);
    }
}
