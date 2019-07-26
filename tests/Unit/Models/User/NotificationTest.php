<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\User\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_user() : void
    {
        $notification = factory(Notification::class)->create([]);
        $this->assertTrue($notification->user()->exists());
    }

    /** @test */
    public function it_belongs_to_a_company() : void
    {
        $company = factory(Company::class)->create([]);
        $notification = factory(Notification::class)->create([
            'company_id' => $company->id,
        ]);

        $this->assertTrue($notification->company()->exists());
    }
}
