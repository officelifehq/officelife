<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $notification = Notification::factory()->create([]);
        $this->assertTrue($notification->employee()->exists());
    }

    /** @test */
    public function it_returns_the_object_attribute(): void
    {
        $notification = Notification::factory()->create([]);
        $this->assertEquals(
            1,
            $notification->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_content_attribute(): void
    {
        $michael = $this->createAdministrator();

        $notification = Notification::factory()->create([
            'action' => 'dummy_data_generated',
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $this->assertEquals(
            'Dummy data have been generated for '.$michael->company->name.'.',
            $notification->content
        );
    }
}
