<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Team;
use App\Models\Company\Position;
use App\Models\Company\EmployeeLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company()
    {
        $employeeLog = factory(EmployeeLog::class)->create([]);
        $this->assertTrue($employeeLog->company()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee()
    {
        $employeeLog = factory(EmployeeLog::class)->create([]);
        $this->assertTrue($employeeLog->employee()->exists());
    }

    /** @test */
    public function it_returns_the_date_attribute()
    {
        $employeeLog = factory(EmployeeLog::class)->create([
            'created_at' => '2017-01-22 17:56:03',
        ]);
        $this->assertEquals(
            'Jan 22, 2017 17:56',
            $employeeLog->date
        );
    }

    /** @test */
    public function it_returns_the_object_attribute()
    {
        $employeeLog = factory(EmployeeLog::class)->create([]);
        $this->assertEquals(
            1,
            $employeeLog->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_author_attribute()
    {
        $adminEmployee = $this->createAdministrator();

        Cache::shouldReceive('get')
            ->once()
            ->times(2)
            ->with('currentCompany')
            ->andReturn($adminEmployee->company);

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'author_id' => $adminEmployee->user->id,
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/employees/'.$adminEmployee->user->id).'">'.$adminEmployee->user->name.'</a>',
            $employeeLog->author
        );

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'author_id' => 12345,
                'author_name' => 'Dwight Schrute',
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            'Dwight Schrute',
            $employeeLog->author
        );
    }

    /** @test */
    public function it_returns_the_team_attribute()
    {
        $adminEmployee = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $adminEmployee->company_id,
        ]);

        Cache::shouldReceive('get')
            ->once()
            ->times(2)
            ->with('currentCompany')
            ->andReturn($adminEmployee->company);

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'team_id' => $team->id,
            ]),
            'company_id' => $team->company_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/teams/'.$team->id).'">'.$team->name.'</a>',
            $employeeLog->team
        );

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'team_id' => 12345,
                'team_name' => 'Sales',
            ]),
            'company_id' => $team->company_id,
        ]);

        $this->assertEquals(
            'Sales',
            $employeeLog->team
        );
    }

    /** @test */
    public function it_returns_the_user_attribute()
    {
        $adminEmployee = $this->createAdministrator();

        Cache::shouldReceive('get')
            ->once()
            ->times(2)
            ->with('currentCompany')
            ->andReturn($adminEmployee->company);

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'user_id' => $adminEmployee->user->id,
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/employees/'.$adminEmployee->user->id).'">'.$adminEmployee->user->name.'</a>',
            $employeeLog->user
        );

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'user_id' => 12345,
                'user_name' => 'dwight@dundermifflin.com',
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            'dwight@dundermifflin.com',
            $employeeLog->user
        );
    }

    /** @test */
    public function it_returns_the_manager_attribute()
    {
        $adminEmployee = $this->createAdministrator();

        Cache::shouldReceive('get')
            ->once()
            ->times(2)
            ->with('currentCompany')
            ->andReturn($adminEmployee->company);

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'manager_id' => $adminEmployee->id,
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/employees/'.$adminEmployee->id).'">'.$adminEmployee->name.'</a>',
            $employeeLog->manager
        );

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'manager_id' => 12345,
                'manager_name' => 'Dwight Schrute',
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            'Dwight Schrute',
            $employeeLog->manager
        );
    }

    /** @test */
    public function it_returns_the_direct_report_attribute()
    {
        $adminEmployee = $this->createAdministrator();

        Cache::shouldReceive('get')
            ->once()
            ->times(2)
            ->with('currentCompany')
            ->andReturn($adminEmployee->company);

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'direct_report_id' => $adminEmployee->id,
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/employees/'.$adminEmployee->id).'">'.$adminEmployee->name.'</a>',
            $employeeLog->directReport
        );

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'direct_report_id' => 12345,
                'direct_report_name' => 'Dwight Schrute',
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            'Dwight Schrute',
            $employeeLog->directReport
        );
    }

    /** @test */
    public function it_returns_the_position_attribute()
    {
        $adminEmployee = $this->createAdministrator();
        $position = factory(Position::class)->create([
            'company_id' => $adminEmployee->company_id,
        ]);

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'position_id' => $position->id,
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            $position->title,
            $employeeLog->position
        );

        $employeeLog = factory(EmployeeLog::class)->create([
            'objects' => json_encode([
                'position_id' => 12345,
                'position_title' => 'Assistant to the regional manager',
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            'Assistant to the regional manager',
            $employeeLog->position
        );
    }
}
