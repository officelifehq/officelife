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
                'birthdate' => (! $michael->birthdate) ? null : [
                    'year' => $michael->birthdate->year,
                    'month' => $michael->birthdate->month,
                    'day' => $michael->birthdate->day,
                ],
                'hired_at' => (! $michael->hired_at) ? null : [
                    'year' => $michael->hired_at->year,
                    'month' => $michael->hired_at->month,
                    'day' => $michael->hired_at->day,
                ],
                'twitter_handle' => $michael->twitter_handle,
                'slack_handle' => $michael->slack_handle,
                'max_year' => 2018,
                'timezone' => [
                    'value' => 'Africa/Banjul',
                    'label' => '(UTC +00:00) Africa/Banjul',
                ],
            ],
            $array
        );
    }
}
