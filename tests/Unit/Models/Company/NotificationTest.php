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
}
