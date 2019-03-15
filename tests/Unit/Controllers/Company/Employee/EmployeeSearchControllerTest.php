<?php

namespace Tests\Unit\Controllers\Company\Employee;

use Tests\TestCase;
use App\Models\Company\Employee;

class EmployeeSearchControllerTest extends TestCase
{
    /** @test */
    public function it_returns_the_list_of_potential_managers_for_this_employee()
    {
        $employee = factory(Employee::class)->create([]);
        $this->be($employee->user);
FAIRE LE TEST DE LA RECHERCHE
        $response = $this->json('POST', '/settings/personalization/activitytypes', [
            'name' => 'Movies',
            'activity_type_category_id' => $activityTypeCategory->id,
                        ]);


    }
}
