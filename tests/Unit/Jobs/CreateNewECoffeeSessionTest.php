<?php

namespace Tests\Unit\Jobs;

use App\Jobs\CreateNewECoffeeSession;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CreateNewECoffeeSessionTest extends TestCase
{
    /** @test */
    public function it_launches_a_match_employee_for_ecoffee_process(): void
    {
        Bus::fake();
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $company = Company::factory()->create();
        Employee::factory()->count(2)->create([
            'company_id' => $company->id,
        ]);

        CreateNewECoffeeSession::dispatch($company);

        Bus::assertDispatched(CreateNewECoffeeSession::class);
    }
}
