<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\RemoveDummyData;

class RemoveDummyDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function administrator_can_call_the_service(): void
    {
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        (new RemoveDummyData)->execute($request);
        $this->assertTrue(true);
    }

    /** @test */
    public function hr_cant_call_the_service(): void
    {
        $michael = $this->createHR();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $this->expectException(NotEnoughPermissionException::class);
        (new RemoveDummyData)->execute($request);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        $this->expectException(NotEnoughPermissionException::class);
        (new RemoveDummyData)->execute($request);
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
