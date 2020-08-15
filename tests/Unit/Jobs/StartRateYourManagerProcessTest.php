<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Jobs\StartRateYourManagerProcess;
use App\Jobs\AskEmployeesToRateTheirManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;

class StartRateYourManagerProcessTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_starts_the_rate_your_manager_process(): void
    {
        Queue::fake();

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

        // jim will be the manager of sarah
        $sarah = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $sarah->id,
            'manager_id' => $jim->id,
        ]);

        // we need to manually dispatch and handle this job so we can assert
        $job = new StartRateYourManagerProcess();
        $job->dispatch();
        $job->handle();

        Queue::assertPushed(AskEmployeesToRateTheirManager::class, function ($job) use ($michael) {
            return $job->manager->id === $michael->id;
        });
        Queue::assertPushed(AskEmployeesToRateTheirManager::class, function ($job) use ($jim) {
            return $job->manager->id === $jim->id;
        });
    }
}
