<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\NotificationHelper;
use App\Models\Company\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_manages_the_case_when_dummy_data_has_been_generated(): void
    {
        $adminEmployee = $this->createAdministrator();

        $notification = factory(Notification::class)->create([
            'action' => 'dummy_data_generated',
            'objects' => json_encode([
                'company_name' => $adminEmployee->company->name,
            ]),
            'employee_id' => $adminEmployee->id,
        ]);

        $string = NotificationHelper::process($notification);

        $this->assertIsString($string);

        $this->assertEquals(
            'Dummy data have been generated for '.$adminEmployee->company->name.'.',
            $string
        );
    }
}
