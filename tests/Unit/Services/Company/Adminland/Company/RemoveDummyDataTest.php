<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\RemoveDummyData;

class RemoveDummyDataTest extends TestCase
{
    use DatabaseTransactions;

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
