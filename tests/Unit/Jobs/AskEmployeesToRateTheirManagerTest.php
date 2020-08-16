<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use App\Jobs\AskEmployeesToRateTheirManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;

class AskEmployeesToRateTheirManagerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_asks_every_employee_to_rate_the_given_manager(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1, 10, 10));

        // michael will be the manager of dwight and jim
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ]);
        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $jim->id,
            'manager_id' => $michael->id,
        ]);

        AskEmployeesToRateTheirManager::dispatch($michael);

        $this->assertDatabaseHas('rate_your_manager_surveys', [
            'manager_id' => $michael->id,
            'active' => true,
            'valid_until_at' => '2018-01-04 23:59:59',
        ]);

        $this->assertDatabaseHas('rate_your_manager_answers', [
            'rate_your_manager_survey_id' => 1,
            'employee_id' => $dwight->id,
            'active' => true,
            'rating' => null,
            'comment' => null,
            'reveal_identity_to_manager' => false,
        ]);

        $this->assertDatabaseHas('rate_your_manager_answers', [
            'rate_your_manager_survey_id' => 1,
            'employee_id' => $jim->id,
            'active' => true,
            'rating' => null,
            'comment' => null,
            'reveal_identity_to_manager' => false,
        ]);
    }
}
