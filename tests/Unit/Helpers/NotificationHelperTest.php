<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Models\Company\Team;
use Illuminate\Support\Collection;
use App\Helpers\NotificationHelper;
use App\Models\Company\Notification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_notifications_for_this_employee_as_a_collection(): void
    {
        $michael = $this->createAdministrator();

        factory(Notification::class, 3)->create([
            'action' => 'dummy_data_generated',
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        factory(Notification::class, 2)->create([
            'action' => 'dummy_data_generated',
            'read' => true,
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $notifications = NotificationHelper::getNotifications($michael);

        $this->assertEquals(
            3,
            $notifications->count()
        );

        $this->assertInstanceOf(
            Collection::class,
            $notifications
        );
    }

    /** @test */
    public function it_manages_the_case_when_dummy_data_has_been_generated(): void
    {
        $michael = $this->createAdministrator();

        $notification = factory(Notification::class)->create([
            'action' => 'dummy_data_generated',
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $string = NotificationHelper::process($notification);

        $this->assertIsString($string);

        $this->assertEquals(
            'Dummy data have been generated for '.$michael->company->name.'.',
            $string
        );
    }

    /** @test */
    public function it_manages_the_case_when_an_employee_has_been_added_to_the_company(): void
    {
        $michael = $this->createAdministrator();

        $notification = factory(Notification::class)->create([
            'action' => 'employee_added_to_company',
            'objects' => json_encode([
                'company_name' => $michael->company->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $string = NotificationHelper::process($notification);

        $this->assertIsString($string);

        $this->assertEquals(
            'You have been added to '.$michael->company->name.'.',
            $string
        );
    }

    /** @test */
    public function it_manages_the_case_when_an_employee_has_been_added_to_the_team(): void
    {
        $michael = $this->createAdministrator();
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $notification = factory(Notification::class)->create([
            'action' => 'employee_added_to_team',
            'objects' => json_encode([
                'team_name' => $sales->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $string = NotificationHelper::process($notification);

        $this->assertIsString($string);

        $this->assertEquals(
            'You have been added to the team called '.$sales->name.'.',
            $string
        );
    }

    /** @test */
    public function it_manages_the_case_when_an_employee_has_been_removed_from_the_team(): void
    {
        $michael = $this->createAdministrator();
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $notification = factory(Notification::class)->create([
            'action' => 'employee_removed_from_team',
            'objects' => json_encode([
                'team_name' => $sales->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $string = NotificationHelper::process($notification);

        $this->assertIsString($string);

        $this->assertEquals(
            'You have been removed from the team called '.$sales->name.'.',
            $string
        );
    }

    /** @test */
    public function it_manages_the_case_when_an_employee_has_been_promoted_to_a_team_lead(): void
    {
        $michael = $this->createAdministrator();
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $notification = factory(Notification::class)->create([
            'action' => 'team_lead_set',
            'objects' => json_encode([
                'team_name' => $sales->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $string = NotificationHelper::process($notification);

        $this->assertIsString($string);

        $this->assertEquals(
            'You have been assigned as the team lead for the team called '.$sales->name.'.',
            $string
        );
    }

    /** @test */
    public function it_manages_the_case_when_an_employee_has_been_demoted_from_a_position_of_team_lead(): void
    {
        $michael = $this->createAdministrator();
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $notification = factory(Notification::class)->create([
            'action' => 'team_lead_removed',
            'objects' => json_encode([
                'team_name' => $sales->name,
            ]),
            'employee_id' => $michael->id,
        ]);

        $string = NotificationHelper::process($notification);

        $this->assertIsString($string);

        $this->assertEquals(
            'You are not longer the team lead of the team called '.$sales->name.'.',
            $string
        );
    }
}
