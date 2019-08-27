<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Morale;
use App\Models\Company\Worklog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\GenerateDummyData;

class GenerateDummyDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_dummy_data() : void
    {
        $employee = $this->createAdministrator();

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->id,
        ];

        (new GenerateDummyData)->execute($request);

        $count = DB::table('employees')
            ->where('is_dummy', true)
            ->count();

        $this->assertEquals(
            32,
            $count
        );

        $count = DB::table('teams')
            ->where('company_id', $employee->company_id)
            ->where('is_dummy', true)
            ->count();

        $this->assertEquals(
            3,
            $count
        );

        $this->assertDatabaseHas('companies', [
            'has_dummy_data' => true,
        ]);

        $worklogsNumber = Worklog::count();
        $this->assertGreaterThan(1, $worklogsNumber);

        $moraleNumber = Morale::count();
        $this->assertGreaterThan(1, $moraleNumber);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
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
