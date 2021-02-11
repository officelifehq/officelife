<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Bus;
use App\Jobs\CreateNewECoffeeSession;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateNewECoffeeSessionTest extends TestCase
{
    use DatabaseTransactions;

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
