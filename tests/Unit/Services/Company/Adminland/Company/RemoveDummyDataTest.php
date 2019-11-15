<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\RemoveDummyData;
use App\Services\Company\Adminland\Company\GenerateDummyData;

class RemoveDummyDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_all_dummy_data(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = factory(Employee::class)->create([]);

        factory(CompanyPTOPolicy::class)->create([
            'company_id' => $michael->company_id,
            'year' => Carbon::now()->format('Y'),
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        (new GenerateDummyData)->execute($request);
        (new RemoveDummyData)->execute($request);

        $count = DB::table('employees')
            ->where('is_dummy', true)
            ->count();

        $this->assertEquals(
            0,
            $count
        );

        $count = DB::table('teams')
            ->where('company_id', $michael->company_id)
            ->where('is_dummy', true)
            ->count();

        $this->assertEquals(
            0,
            $count
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => 123456,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveDummyData)->execute($request);
    }
}
