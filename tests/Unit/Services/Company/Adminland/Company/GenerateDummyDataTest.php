<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User\User;
use App\Jobs\Dummy\CreateDummyTeam;
use Illuminate\Support\Facades\Queue;
use App\Jobs\Dummy\CreateDummyWorklog;
use App\Jobs\Dummy\CreateDummyPosition;
use App\Models\Company\CompanyPTOPolicy;
use App\Jobs\Dummy\AddDummyEmployeeToCompany;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\GenerateDummyData;

class GenerateDummyDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_and_removes_dummy_data(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        Queue::fake();

        $michael = $this->createAdministrator();

        factory(CompanyPTOPolicy::class)->create([
            'company_id' => $michael->company_id,
            'year' => Carbon::now()->format('Y'),
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        (new GenerateDummyData)->execute($request);

        Queue::assertPushed(CreateDummyPosition::class, 15);
        Queue::assertPushed(CreateDummyTeam::class, 7);
        Queue::assertPushed(AddDummyEmployeeToCompany::class, 17);
        Queue::assertPushed(CreateDummyWorklog::class);

        $this->assertDatabaseHas('companies', [
            'has_dummy_data' => true,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $user = factory(User::class)->create([]);

        $request = [
            'company_id' => $user->company_id,
            'author_id' => 1234556,
        ];

        $this->expectException(ValidationException::class);
        (new GenerateDummyData)->execute($request);
    }
}
