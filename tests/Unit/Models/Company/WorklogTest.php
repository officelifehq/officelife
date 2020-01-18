<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorklogTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $worklog = factory(Worklog::class)->create([]);
        $this->assertTrue($worklog->employee()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $michael = factory(Employee::class)->create([
            'first_name' => 'michael',
            'last_name' => 'scott',
        ]);
        $worklog = factory(Worklog::class)->create([
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
