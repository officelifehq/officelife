<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeEditViewHelper;

class EmployeeEditViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_information_about_the_employee(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = Employee::factory()->create([
            'timezone' => 'Africa/Banjul',
            'hired_at' => '1900-01-01',
            'birthdate' => '1900-01-01',
        ]);

        $array = EmployeeEditViewHelper::show($michael);

        $this->assertEquals(
            [
                'id' => $michael->id,
                'first_name' => $michael->first_name,
                'last_name' => $michael->last_name,
                'name' => $michael->name,
                'email' => $michael->email,
                'phone' => $michael->phone_number,
                'birthdate' => (! $michael->birthdate) ? null : '1900-01-01',
                'hired_at' => (! $michael->birthdate) ? null : '1900-01-01',
                'twitter_handle' => $michael->twitter_handle,
                'slack_handle' => $michael->slack_handle,
                'max_year' => 2018,
                'timezone' => 'Africa/Banjul',
            ],
            $array
        );
    }
}
