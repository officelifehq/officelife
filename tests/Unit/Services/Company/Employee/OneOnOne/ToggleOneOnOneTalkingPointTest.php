<?php

namespace Tests\Unit\Services\Company\Employee\OneOnOne;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\OneOnOneTalkingPoint;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\OneOnOne\ToggleOneOnOneTalkingPoint;

class ToggleOneOnOneTalkingPointTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_toggles_an_one_on_one_talking_point_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);
        $entry = factory(OneOnOneEntry::class)->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $talkingPoint = factory(OneOnOneTalkingPoint::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $talkingPoint);
    }

    /** @test */
    public function it_toggles_an_one_on_one_talking_point_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createDirectReport($michael);
        $entry = factory(OneOnOneEntry::class)->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $talkingPoint = factory(OneOnOneTalkingPoint::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $talkingPoint);
    }

    /** @test */
    public function normal_user_can_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createDirectReport($michael);
        $entry = factory(OneOnOneEntry::class)->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);
        $talkingPoint = factory(OneOnOneTalkingPoint::class)->create([
            'one_on_one_entry_id' => $entry->id,
        ]);
        $this->executeService($michael, $entry, $talkingPoint);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new ToggleOneOnOneTalkingPoint)->execute($request);
    }

    private function executeService(Employee $michael, OneOnOneEntry $entry, OneOnOneTalkingPoint $talkingPoint): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'one_on_one_entry_id' => $entry->id,
            'one_on_one_talking_point_id' => $talkingPoint->id,
        ];

        $talkingPoint = (new ToggleOneOnOneTalkingPoint)->execute($request);

        $this->assertInstanceOf(
            OneOnOneTalkingPoint::class,
            $talkingPoint
        );

        $this->assertDatabaseHas('one_on_one_talking_points', [
            'id' => $talkingPoint->id,
            'checked' => true,
        ]);
    }
}
