<?php

namespace Tests\Unit\Services\Account\Account;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Company\RemoveDummyData;
use App\Services\Company\Company\GenerateDummyData;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RemoveDummyDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_all_dummy_data()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
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
            ->where('company_id', $employee->company_id)
            ->where('is_dummy', true)
            ->count();

        $this->assertEquals(
            0,
            $count
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => 123456,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveDummyData)->execute($request);
    }
}
