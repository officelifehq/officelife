<?php

namespace Tests\Unit\ViewHelpers\Company\Group;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Group\GroupCreateViewHelper;

class GroupCreateViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_searches_employees_to_assign_to_a_group(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
        ]);
        $dwight = Employee::factory()->create([
            'first_name' => 'alb',
            'last_name' => 'bli',
            'email' => 'alb@bli',
            'company_id' => $michael->company_id,
        ]);
        // the following should not be included in the search results
        Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
            'locked' => true,
            'company_id' => $michael->company_id,
        ]);

        $collection = GroupCreateViewHelper::search($michael->company, 'e');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 23),
                ],
            ],
            $collection->toArray()
        );

        $collection = GroupCreateViewHelper::search($michael->company, 'bli');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'avatar' => ImageHelper::getAvatar($dwight, 23),
                ],
            ],
            $collection->toArray()
        );
    }
}
