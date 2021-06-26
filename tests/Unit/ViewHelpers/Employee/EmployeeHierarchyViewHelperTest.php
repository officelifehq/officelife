<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Employee\EmployeeHierarchyViewHelper;

class EmployeeHierarchyViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_searches_employees(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
            'permission_level' => 100,
        ]);
        $otherEmployee = Employee::factory()->create([
            'first_name' => 'Jim',
            'last_name' => 'Halper',
            'email' => 'jim@halpert',
            'company_id' => $michael->company_id,
        ]);
        $manager = Employee::factory()->create([
            'first_name' => 'manager',
            'last_name' => 'boss',
            'email' => 'manager@boss',
            'company_id' => $michael->company_id,
        ]);
        $directReport = Employee::factory()->create([
            'first_name' => 'directreport',
            'last_name' => 'slave',
            'email' => 'directreport@slave',
            'company_id' => $michael->company_id,
        ]);
        // the following should not be included in the search results
        Employee::factory()->create([
            'first_name' => 'cle',
            'last_name' => 'dle',
            'email' => 'cle@dle',
            'locked' => true,
            'company_id' => $michael->company_id,
        ]);

        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'manager_id' => $manager->id,
        ]);
        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $directReport->id,
            'manager_id' => $michael->id,
        ]);

        $collection = EmployeeHierarchyViewHelper::search($michael->company, $michael, 'e');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $otherEmployee->id,
                    'name' => $otherEmployee->name,
                ],
            ],
            $collection->toArray()
        );
    }
}
