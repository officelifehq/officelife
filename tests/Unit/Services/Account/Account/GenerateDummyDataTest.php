<?php

namespace Tests\Unit\Services\Company\Company;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Company\GenerateDummyData;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenerateDummyDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_dummy_data()
    {
        $employee = $this->createAdministrator();

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user->id,
        ];

        (new GenerateDummyData)->execute($request);

        $count = DB::table('users')
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
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
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
