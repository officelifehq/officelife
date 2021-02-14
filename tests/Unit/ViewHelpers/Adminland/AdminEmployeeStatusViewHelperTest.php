<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\EmployeeStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminEmployeeStatusViewHelper;

class AdminEmployeeStatusViewHelperTest extends TestCase
{
    use DatabaseTransactions;

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
                    'type_translated' => 'internal',
                ],
            ],
            $collection->toArray()
        );
    }
}
