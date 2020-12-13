<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
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

        $this->assertEquals(
            [
                0 => [
                    'id' => 1,
                    'name' => 'Permanent',
                    'type' => 'internal',
                    'type_translated' => 'internal',
                ],
            ],
            $collection->toArray()
        );
    }
}
