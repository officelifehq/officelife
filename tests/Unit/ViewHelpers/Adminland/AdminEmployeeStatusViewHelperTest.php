<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use App\Http\ViewHelpers\Adminland\AdminEmployeeStatusViewHelper;
use App\Models\Company\EmployeeStatus;
use Tests\TestCase;

class AdminEmployeeStatusViewHelperTest extends TestCase
{
    /** @test */
    public function it_gets_information_about_the_employee_statuses(): void
    {
        $michael = $this->createAdministrator();

        $collection = AdminEmployeeStatusViewHelper::index($michael->company);

        $status = EmployeeStatus::first();

        $this->assertEquals(
            [
                0 => [
                    'id' => $status->id,
                    'name' => 'Permanent',
                    'type' => 'internal',
                ],
            ],
            $collection->toArray()
        );
    }
}
