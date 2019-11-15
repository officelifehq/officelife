<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Morale;
use App\Models\Company\Employee;
use App\Jobs\ProcessCompanyMorale;
use App\Models\Company\MoraleCompanyHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProcessCompanyMoraleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_the_statistics_about_how_employees_feel(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        factory(Morale::class)->create([
            'employee_id' => $michael->id,
            'emotion' => 1,
        ]);

        factory(Morale::class)->create([
            'employee_id' => $dwight->id,
            'emotion' => 3,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'date' => Carbon::now(),
        ];

        ProcessCompanyMorale::dispatch($request);

        $this->assertEquals(
            1,
            MoraleCompanyHistory::get()->count()
        );

        $this->assertDatabaseHas('morale_company_history', [
            'company_id' => $michael->company_id,
            'average' => 2,
            'number_of_employees' => 2,
        ]);

        // check that the job runs only once per day
        ProcessCompanyMorale::dispatch($request);

        $this->assertEquals(
            1,
            MoraleCompanyHistory::get()->count()
        );
    }
}
