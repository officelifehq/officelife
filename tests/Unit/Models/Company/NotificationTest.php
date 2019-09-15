<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee() : void
    {
        $notification = factory(Notification::class)->create([]);
        $this->assertTrue($notification->employee()->exists());
    }

    /** @test */
    public function it_returns_the_object_attribute() : void
    {
        $notification = factory(Notification::class)->create([]);
        $this->assertEquals(
            1,
            $notification->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_content_attribute() : void
    {
        $adminEmployee = $this->createAdministrator();

        $notification = factory(Notification::class)->create([
            'action' => 'dummy_data_generated',
            'objects' => json_encode([
                'company_name' => $adminEmployee->company->name,
            ]),
            'employee_id' => $adminEmployee->id,
        ]);

        $this->assertEquals(
            'Dummy data have been generated for '.$adminEmployee->company->name.'.',
            $notification->content
        );
    }
}
