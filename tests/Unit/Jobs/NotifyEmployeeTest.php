<?php

namespace Tests\Unit\Jobs;

use App\Jobs\NotifyEmployee;
use Tests\TestCase;

class NotifyEmployeeTest extends TestCase
{
    /** @test */
    public function it_logs_a_notification(): void
    {
        $michael = $this->createAdministrator();

        $request = [
            'employee_id' => $michael->id,
            'action' => 'task_assigned',
            'objects' => json_encode([
                'author_id' => $michael->id,
                'author_name' => $michael->name,
                'company_name' => $michael->company->name,
            ]),
        ];

        NotifyEmployee::dispatch($request);

        $this->assertDatabaseHas('notifications', [
            'employee_id' => $michael->id,
            'action' => 'task_assigned',
            'objects' => json_encode([
                'author_id' => $michael->id,
                'author_name' => $michael->name,
                'company_name' => $michael->company->name,
            ]),
        ]);
    }
}
