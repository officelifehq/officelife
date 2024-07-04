<?php

namespace Tests\Unit\Jobs;

use App\Jobs\UpdateDashboardPreference;
use Tests\TestCase;

class UpdateDashboardPreferenceTest extends TestCase
{
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
