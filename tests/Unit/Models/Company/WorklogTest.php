<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Employee;
use App\Models\Company\Worklog;
use Tests\TestCase;

class WorklogTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $worklog = Worklog::factory()->create();
        $this->assertTrue($worklog->employee()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'michael',
            'last_name' => 'scott',
        ]);
        $worklog = Worklog::factory()->create([
            'employee_id' => $michael->id,
            'content' => 'a content',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $worklog->id,
                'content' => 'a content',
                'parsed_content' => '<p>a content</p>',
                'employee' => [
                    'id' => $michael->id,
                    'name' => 'michael scott',
                ],
                'localized_created_at' => 'Jan 12, 2020',
                'created_at' => '2020-01-12 00:00:00',
            ],
            $worklog->toObject()
        );
    }
}
